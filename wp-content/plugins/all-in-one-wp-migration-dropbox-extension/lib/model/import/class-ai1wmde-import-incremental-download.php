<?php
/**
 * Copyright (C) 2014-2020 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wmde_Import_Incremental_Download {

	public static function execute( $params, Ai1wmde_Dropbox_Client $dropbox = null ) {

		$params['completed'] = false;

		// Validate file path
		if ( ! isset( $params['file_index'] ) ) {
			throw new Ai1wm_Import_Exception( __( 'Dropbox File Index is not specified.', AI1WMDE_PLUGIN_NAME ) );
		}

		// Validate file path
		if ( ! isset( $params['file_path'] ) ) {
			throw new Ai1wm_Import_Exception( __( 'Dropbox File Path is not specified.', AI1WMDE_PLUGIN_NAME ) );
		}

		// Validate file size
		if ( ! isset( $params['file_size'] ) ) {
			throw new Ai1wm_Import_Exception( __( 'Dropbox File Size is not specified.', AI1WMDE_PLUGIN_NAME ) );
		}

		// Set file chunk size for download
		$file_chunk_size = get_option( 'ai1wmde_dropbox_file_chunk_size', AI1WMDE_DEFAULT_FILE_CHUNK_SIZE );

		// Set archive offset
		if ( ! isset( $params['archive_offset'] ) ) {
			$params['archive_offset'] = 0;
		}

		// Set file range start
		if ( ! isset( $params['file_range_start'] ) ) {
			$params['file_range_start'] = 0;
		}

		// Set file range end
		if ( ! isset( $params['file_range_end'] ) ) {
			$params['file_range_end'] = $file_chunk_size - 1;
		}

		// Set incremental index
		if ( ! isset( $params['incremental_index'] ) ) {
			$params['incremental_index'] = 0;
		}

		// Set download retries
		if ( ! isset( $params['download_retries'] ) ) {
			$params['download_retries'] = 0;
		}

		// Set download backoff
		if ( ! isset( $params['download_backoff'] ) ) {
			$params['download_backoff'] = 1;
		}

		// Set Dropbox client
		if ( is_null( $dropbox ) ) {
			$dropbox = new Ai1wmde_Dropbox_Client(
				get_option( 'ai1wmde_dropbox_token', false ),
				get_option( 'ai1wmde_dropbox_ssl', true )
			);
		}

		// Open the archive file for writing
		if ( ( $archive = fopen( ai1wm_archive_path( $params ), 'cb' ) ) ) {
			if ( ( fseek( $archive, $params['archive_offset'] ) !== -1 ) ) {
				try {

					$params['download_retries'] += 1;
					$params['download_backoff'] *= 2;

					// Download file chunk data
					$dropbox->get_file( $archive, sprintf( '%s/%s', $params['folder_path'], $params['file_path'] ), $params['file_range_start'], $params['file_range_end'] );

					// Unset download retries
					unset( $params['download_retries'] );
					unset( $params['download_backoff'] );

				} catch ( Ai1wmde_Connect_Exception $e ) {
					sleep( ( $dropbox->get_retry_after() ? $dropbox->get_retry_after() : $params['download_backoff'] ) );
					if ( $params['download_retries'] <= 3 ) {
						return $params;
					}

					throw $e;
				}

				// Set archive offset
				$params['archive_offset'] = ftell( $archive );

				// Set file range start
				$params['file_range_start'] = min( $params['file_range_start'] + $file_chunk_size, $params['file_size'] - 1 );

				// Set file range end
				$params['file_range_end'] = min( $params['file_range_end'] + $file_chunk_size, $params['file_size'] - 1 );
			}

			// Get progress
			$progress = (int) ( ( $params['archive_offset'] / $params['total_backups_files_size'] ) * 100 );

			// Set progress
			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::log( sprintf( __( 'Downloading %s (%s) [%d%% complete]', AI1WMDE_PLUGIN_NAME ), $params['file_path'], $params['file_size'], $progress ) );
			} else {
				Ai1wm_Status::progress( $progress );
			}

			// Completed?
			if ( $params['file_range_start'] === ( $params['file_size'] - 1 ) ) {
				$incremental_files = array();

				// Get incremental files
				if ( ( $incremental_list = ai1wm_open( ai1wm_incremental_backups_list_path( $params ), 'rb' ) ) ) {
					while ( list( $file_index, $file_path, $file_size, $file_mtime ) = fgetcsv( $incremental_list ) ) {
						$incremental_files[ $file_index ] = array( $file_path, $file_size, $file_mtime );
					}

					ai1wm_close( $incremental_list );
				}

				// Unset file parameters
				if ( ++$params['incremental_index'] <= $params['file_index'] ) {
					if ( isset( $incremental_files[ $params['incremental_index'] ][0] ) ) {
						$params['file_path'] = $incremental_files[ $params['incremental_index'] ][0];
					}

					if ( isset( $incremental_files[ $params['incremental_index'] ][1] ) ) {
						$params['file_size'] = $incremental_files[ $params['incremental_index'] ][1];
					}

					if ( isset( $params['archive_offset'] ) ) {
						$params['archive_offset'] -= 4377;
					}

					unset( $params['file_range_start'] );
					unset( $params['file_range_end'] );
				} else {
					unset( $params['file_path'] );
					unset( $params['file_size'] );
					unset( $params['file_range_start'] );
					unset( $params['file_range_end'] );
					unset( $params['archive_offset'] );
					unset( $params['incremental_index'] );
					unset( $params['completed'] );
				}
			}

			fclose( $archive );
		}

		return $params;
	}
}

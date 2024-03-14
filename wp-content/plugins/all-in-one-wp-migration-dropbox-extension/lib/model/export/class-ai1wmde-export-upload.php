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

class Ai1wmde_Export_Upload {

	public static function execute( $params, Ai1wmde_Dropbox_Client $dropbox = null ) {

		$params['completed'] = false;

		// Set archive offset
		if ( ! isset( $params['archive_offset'] ) ) {
			$params['archive_offset'] = 0;
		}

		// Set archive size
		if ( ! isset( $params['archive_size'] ) ) {
			$params['archive_size'] = ai1wm_archive_bytes( $params );
		}

		// Set upload retries
		if ( ! isset( $params['upload_retries'] ) ) {
			$params['upload_retries'] = 0;
		}

		// Set upload backoff
		if ( ! isset( $params['upload_backoff'] ) ) {
			$params['upload_backoff'] = 1;
		}

		// Set Dropbox client
		if ( is_null( $dropbox ) ) {
			$dropbox = new Ai1wmde_Dropbox_Client(
				get_option( 'ai1wmde_dropbox_token', false ),
				get_option( 'ai1wmde_dropbox_ssl', true )
			);
		}

		// Open the archive file for reading
		if ( ( $archive = fopen( ai1wm_archive_path( $params ), 'rb' ) ) ) {

			// Set file chunk size for upload
			$file_chunk_size = get_option( 'ai1wmde_dropbox_file_chunk_size', AI1WMDE_DEFAULT_FILE_CHUNK_SIZE );

			// Read file chunk data
			if ( ( fseek( $archive, $params['archive_offset'] ) !== -1 )
					&& ( $file_chunk_data = fread( $archive, $file_chunk_size ) ) ) {

				try {

					$params['upload_retries'] += 1;
					$params['upload_backoff'] *= 2;

					// Upload file in one chunk if <= 4MB
					if ( $params['archive_size'] <= $file_chunk_size ) {
						try {
							$dropbox->upload_file( $file_chunk_data, sprintf( '%s/%s', $params['folder_path'], ai1wm_archive_name( $params ) ) );
						} catch ( Ai1wmde_Connect_Exception $e ) {
							throw $e;
						}
					} elseif ( $params['archive_offset'] === 0 ) {
						try {
							$params['session_id'] = $dropbox->upload_first_file_chunk( $file_chunk_data );
						} catch ( Ai1wmde_Connect_Exception $e ) {
							throw $e;
						}
					} elseif ( $params['archive_size'] > ( $params['archive_offset'] + $file_chunk_size ) ) {
						try {
							$dropbox->upload_next_file_chunk( $file_chunk_data, $params['session_id'], $params['archive_offset'] );
						} catch ( Ai1wmde_Incorrect_Offset_Exception $e ) {
							throw $e;
						}
					} else {
						try {
							$dropbox->upload_file_chunk_commit( $file_chunk_data, sprintf( '%s/%s', $params['folder_path'], ai1wm_archive_name( $params ) ), $params['session_id'], $params['archive_offset'] );
						} catch ( Ai1wmde_Lookup_Failed_Exception $e ) {
							throw $e;
						}
					}

					// Unset upload retries
					unset( $params['upload_retries'] );
					unset( $params['upload_backoff'] );

				} catch ( Ai1wmde_Connect_Exception $e ) {
					sleep( ( $dropbox->get_retry_after() ? $dropbox->get_retry_after() : $params['upload_backoff'] ) );
					if ( $params['upload_retries'] <= 3 ) {
						return $params;
					}

					throw $e;
				} catch ( Ai1wmde_Incorrect_Offset_Exception $e ) {
					$params['archive_offset'] = $dropbox->get_correct_offset();
					if ( $params['upload_retries'] <= 3 ) {
						return $params;
					}

					throw $e;
				}

				// Set archive offset
				$params['archive_offset'] = ftell( $archive );

				// Set archive details
				$name = ai1wm_archive_name( $params );
				$size = ai1wm_archive_size( $params );

				// Get progress
				$progress = (int) ( ( $params['archive_offset'] / $params['archive_size'] ) * 100 );

				// Set progress
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::log( sprintf( __( 'Uploading %s (%s) [%d%% complete]', AI1WMDE_PLUGIN_NAME ), $name, $size, $progress ) );
				} else {
					Ai1wm_Status::info( sprintf( __( '<i class="ai1wmde-icon-dropbox"></i> Uploading <strong>%s</strong> (%s)<br />%d%% complete', AI1WMDE_PLUGIN_NAME ), $name, $size, $progress ) );
				}
			} else {

				// Set last backup date
				update_option( 'ai1wmde_dropbox_timestamp', time() );

				// Unset file parameters
				unset( $params['session_id'] );
				unset( $params['archive_offset'] );
				unset( $params['archive_size'] );
				unset( $params['completed'] );
			}

			fclose( $archive );
		}

		return $params;
	}
}

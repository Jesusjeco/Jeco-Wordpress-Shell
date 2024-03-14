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

class Ai1wmde_Import_Controller {

	public static function button() {
		return Ai1wm_Template::get_content(
			'import/button',
			array( 'token' => get_option( 'ai1wmde_dropbox_token', false ) ),
			AI1WMDE_TEMPLATES_PATH
		);
	}

	public static function picker() {
		Ai1wm_Template::render(
			'import/picker',
			array(),
			AI1WMDE_TEMPLATES_PATH
		);
	}

	public static function browser( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( $_GET );
		}

		// Set folder path
		$folder_path = null;
		if ( isset( $params['folder_path'] ) ) {
			$folder_path = trim( $params['folder_path'] );
		}

		// Set Dropbox client
		$dropbox = new Ai1wmde_Dropbox_Client(
			get_option( 'ai1wmde_dropbox_token', false ),
			get_option( 'ai1wmde_dropbox_ssl', true )
		);

		try {
			$response = $dropbox->list_folder( $folder_path, array( 'folder', 'file' => '/\.wpress$/' ) );
		} catch ( Ai1wmde_Error_Exception $e ) {
		}

		$items = array();
		if ( isset( $response['items'] ) ) {
			foreach ( $response['items'] as $item ) {
				$items[] = array(
					'index' => null,
					'name'  => isset( $item['name'] ) ? $item['name'] : null,
					'path'  => isset( $item['path'] ) ? $item['path'] : null,
					'date'  => isset( $item['date'] ) ? human_time_diff( $item['date'] ) : null,
					'size'  => isset( $item['bytes'] ) ? ai1wm_size_format( $item['bytes'] ) : null,
					'bytes' => isset( $item['bytes'] ) ? $item['bytes'] : null,
					'type'  => isset( $item['type'] ) ? $item['type'] : null,
				);
			}
		}

		echo json_encode( array( 'items' => $items, 'cursor' => ( isset( $response['cursor'] ) ? $response['cursor'] : null ) ) );
		exit;
	}

	public static function incremental( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( $_GET );
		}

		// Set folder path
		$folder_path = null;
		if ( isset( $params['folder_path'] ) ) {
			$folder_path = trim( $params['folder_path'] );
		}

		// Set Dropbox client
		$dropbox = new Ai1wmde_Dropbox_Client(
			get_option( 'ai1wmde_dropbox_token', false ),
			get_option( 'ai1wmde_dropbox_ssl', true )
		);

		try {
			$response = $dropbox->get_file_content( sprintf( '%s/incremental.backups.list', $folder_path ) );
		} catch ( Ai1wmde_Error_Exception $e ) {
		}

		$items = array();
		if ( isset( $response ) ) {
			foreach ( str_getcsv( $response, "\n" ) as $row ) {
				if ( list( $file_index, $file_path, $file_size, $file_mtime ) = str_getcsv( $row ) ) {
					$items[] = array(
						'index'  => $file_index,
						'name'   => sprintf( __( 'Restore point %d', AI1WMDE_PLUGIN_NAME ), $file_index ),
						'path'   => $file_path,
						'folder' => $folder_path,
						'date'   => get_date_from_gmt( date( 'Y-m-d H:i:s', $file_mtime ), 'M j, Y g:i a' ),
						'size'   => ai1wm_size_format( $file_size ),
						'bytes'  => $file_size,
						'type'   => 'file',
					);
				}
			}
		}

		echo json_encode( array( 'items' => array_reverse( $items ), 'cursor' => null ) );
		exit;
	}
}

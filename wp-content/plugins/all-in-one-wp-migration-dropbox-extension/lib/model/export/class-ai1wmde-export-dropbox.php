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

class Ai1wmde_Export_Dropbox {

	public static function execute( $params, Ai1wmde_Dropbox_Client $dropbox = null ) {

		// Set progress
		Ai1wm_Status::info( __( 'Connecting to Dropbox...', AI1WMDE_PLUGIN_NAME ) );

		// Open the archive file for writing
		$archive = new Ai1wm_Compressor( ai1wm_archive_path( $params ) );

		// Append EOF block
		$archive->close( true );

		// Set Dropbox client
		if ( is_null( $dropbox ) ) {
			$dropbox = new Ai1wmde_Dropbox_Client(
				get_option( 'ai1wmde_dropbox_token', false ),
				get_option( 'ai1wmde_dropbox_ssl', true )
			);
		}

		// Create folder path
		if ( ai1wmde_is_incremental() ) {
			if ( ! ( $params['folder_path'] = $dropbox->get_folder_path_by_path( sprintf( '%s/incremental-backups', ai1wmde_get_folder_path() ) ) ) ) {
				$params['folder_path'] = $dropbox->create_folder( sprintf( '%s/incremental-backups', ai1wmde_get_folder_path() ) );
			}
		} else {
			if ( ! ( $params['folder_path'] = $dropbox->get_folder_path_by_path( ai1wmde_get_folder_path() ) ) ) {
				$params['folder_path'] = $dropbox->create_folder( ai1wmde_get_folder_path() );
			}
		}

		// Set progress
		Ai1wm_Status::info( __( 'Done connecting to Dropbox.', AI1WMDE_PLUGIN_NAME ) );

		return $params;
	}
}

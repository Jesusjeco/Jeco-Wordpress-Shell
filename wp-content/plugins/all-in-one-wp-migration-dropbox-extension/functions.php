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

/**
 * Check whether export/import is running
 *
 * @return boolean
 */
function ai1wmde_is_running() {
	if ( isset( $_GET['dropbox'] ) || isset( $_POST['dropbox'] ) ) {
		return true;
	}

	return false;
}

/**
 * Check whether export/import is incremental
 *
 * @return boolean
 */
function ai1wmde_is_incremental() {
	if ( isset( $_GET['dropbox'], $_GET['incremental'] ) || isset( $_POST['dropbox'], $_POST['incremental'] ) ) {
		return true;
	}

	return false;
}

/**
 * Get Dropbox folder path
 *
 * @return string
 */
function ai1wmde_get_folder_path() {
	if ( ! ( $folder_path = get_option( 'ai1wmde_dropbox_folder_path', false ) ) ) {
		$folder_path = sprintf( '/%s', ai1wm_archive_folder() );
	}

	return $folder_path;
}

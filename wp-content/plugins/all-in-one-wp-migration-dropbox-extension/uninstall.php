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

// Include plugin bootstrap file
require_once dirname( __FILE__ ) .
	DIRECTORY_SEPARATOR .
	'all-in-one-wp-migration-dropbox-extension.php';

/**
 * Trigger Uninstall process only if WP_UNINSTALL_PLUGIN is defined
 */
if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	global $wpdb, $wp_filesystem;

	// Reset cron schedules
	if ( ( $cron = get_option( 'cron', array() ) ) ) {
		foreach ( $cron as $timestamp => $hooks ) {
			if ( isset( $cron[ $timestamp ]['ai1wmde_dropbox_hourly_export'] ) ) {
				unset( $cron[ $timestamp ]['ai1wmde_dropbox_hourly_export'] );
			}

			if ( isset( $cron[ $timestamp ]['ai1wmde_dropbox_daily_export'] ) ) {
				unset( $cron[ $timestamp ]['ai1wmde_dropbox_daily_export'] );
			}

			if ( isset( $cron[ $timestamp ]['ai1wmde_dropbox_weekly_export'] ) ) {
				unset( $cron[ $timestamp ]['ai1wmde_dropbox_weekly_export'] );
			}

			if ( isset( $cron[ $timestamp ]['ai1wmde_dropbox_monthly_export'] ) ) {
				unset( $cron[ $timestamp ]['ai1wmde_dropbox_monthly_export'] );
			}
		}

		update_option( 'cron', $cron );
	}

	// Delete any options or other data stored in the database here
	delete_option( 'ai1wmde_dropbox_cron_timestamp' );
	delete_option( 'ai1wmde_dropbox_cron' );
	delete_option( 'ai1wmde_dropbox_token' );
	delete_option( 'ai1wmde_dropbox_ssl' );
	delete_option( 'ai1wmde_dropbox_folder_path' );
	delete_option( 'ai1wmde_dropbox_folder_shared_link' );
	delete_option( 'ai1wmde_dropbox_backups' );
	delete_option( 'ai1wmde_dropbox_total' );
	delete_option( 'ai1wmde_dropbox_days' );
	delete_option( 'ai1wmde_dropbox_incremental' );
	delete_option( 'ai1wmde_dropbox_file_chunk_size' );
	delete_option( 'ai1wmde_dropbox_notify_toggle' );
	delete_option( 'ai1wmde_dropbox_notify_error_toggle' );
	delete_option( 'ai1wmde_dropbox_notify_error_subject' );
	delete_option( 'ai1wmde_dropbox_notify_email' );
}

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

class Ai1wmde_Settings {

	public function revoke() {
		// Set Dropbox client
		$dropbox = new Ai1wmde_Dropbox_Client(
			get_option( 'ai1wmde_dropbox_token', false ),
			get_option( 'ai1wmde_dropbox_ssl', true )
		);

		// Revoke token
		$dropbox->revoke();

		// Remove token option
		delete_option( 'ai1wmde_dropbox_token' );

		// Remove cron option
		delete_option( 'ai1wmde_dropbox_cron' );

		// Reset cron schedules
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_hourly_export' );
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_daily_export' );
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_weekly_export' );
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_monthly_export' );
	}

	public function revoke_shared_link( $shared_link ) {
		$dropbox = new Ai1wmde_Dropbox_Client(
			get_option( 'ai1wmde_dropbox_token', false ),
			get_option( 'ai1wmde_dropbox_ssl', true )
		);

		return $dropbox->revoke_shared_link( $shared_link );
	}

	public function create_shared_link( $folder_path ) {
		$dropbox = new Ai1wmde_Dropbox_Client(
			get_option( 'ai1wmde_dropbox_token', false ),
			get_option( 'ai1wmde_dropbox_ssl', true )
		);

		return $dropbox->create_shared_link( $folder_path );
	}

	public function get_last_backup_date( $last_backup_timestamp ) {
		if ( $last_backup_timestamp ) {
			$last_backup_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $last_backup_timestamp ), 'F j, Y g:i a' );
		} else {
			$last_backup_date = __( 'None', AI1WMDE_PLUGIN_NAME );
		}

		return $last_backup_date;
	}

	public function get_next_backup_date( $schedules ) {
		$future_backup_timestamps = array();

		// Get next scheduled event
		foreach ( $schedules as $schedule ) {
			$future_backup_timestamps[] = wp_next_scheduled( "ai1wmde_dropbox_{$schedule}_export", array( $this->get_cron_args() ) );
		}

		sort( $future_backup_timestamps );

		if ( isset( $future_backup_timestamps[0] ) ) {
			$next_backup_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $future_backup_timestamps[0] ), 'F j, Y g:i a' );
		} else {
			$next_backup_date = __( 'None', AI1WMDE_PLUGIN_NAME );
		}

		return $next_backup_date;
	}

	public function get_account( Ai1wmde_Dropbox_Client $dropbox = null ) {
		// Set Dropbox client
		if ( is_null( $dropbox ) ) {
			$dropbox = new Ai1wmde_Dropbox_Client(
				get_option( 'ai1wmde_dropbox_token', false ),
				get_option( 'ai1wmde_dropbox_ssl', true )
			);
		}

		// Get account info
		$account = $dropbox->get_account_info();

		// Get space usage info
		$usage = $dropbox->get_usage_info();

		// Set account name
		$name = null;
		if ( isset( $account['name']['display_name'] ) ) {
			$name = $account['name']['display_name'];
		}

		// Set used quota
		$used = 1;
		if ( isset( $usage['used'] ) ) {
			$used = $usage['used'];
		}

		// Set total quota
		$total = 1;
		if ( isset( $usage['allocation']['allocated'] ) ) {
			$total = $usage['allocation']['allocated'];
		}

		// Set email
		$email = null;
		if ( isset( $account['email'] ) ) {
			$email = $account['email'];
		}

		return array(
			'name'     => $name,
			'email'    => $email,
			'used'     => ai1wm_size_format( $used ),
			'total'    => ai1wm_size_format( $total ),
			'progress' => ceil( ( $used / $total ) * 100 ),
		);
	}

	public function set_cron_timestamp( $timestamp ) {
		return update_option( 'ai1wmde_dropbox_cron_timestamp', $timestamp );
	}

	public function get_cron_timestamp() {
		return get_option( 'ai1wmde_dropbox_cron_timestamp', time() );
	}

	/**
	 * Set cron schedules
	 *
	 * @param  array   $schedules List of schedules
	 * @return boolean
	 */
	public function set_cron( $schedules ) {
		ai1wm_cache_flush();

		// Reset cron schedules
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_hourly_export' );
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_daily_export' );
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_weekly_export' );
		Ai1wm_Cron::clear( 'ai1wmde_dropbox_monthly_export' );

		// Update cron schedules
		foreach ( $schedules as $schedule ) {
			Ai1wm_Cron::add( "ai1wmde_dropbox_{$schedule}_export", $schedule, $this->get_cron_timestamp(), array( $this->get_cron_args() ) );
		}

		return update_option( 'ai1wmde_dropbox_cron', $schedules );
	}

	public function get_cron() {
		return get_option( 'ai1wmde_dropbox_cron', array() );
	}

	public function init_cron() {
		foreach ( $this->get_cron() as $schedule ) {
			if ( ! Ai1wm_Cron::exists( "ai1wmde_dropbox_{$schedule}_export", array( $this->get_cron_args() ) ) ) {
				Ai1wm_Cron::clear( "ai1wmde_dropbox_{$schedule}_export" );
				Ai1wm_Cron::add( "ai1wmde_dropbox_{$schedule}_export", $schedule, $this->get_cron_timestamp(), array( $this->get_cron_args() ) );
			}
		}
	}

	public function get_cron_args() {
		if ( $this->get_incremental() ) {
			return array( 'secret_key' => get_option( AI1WM_SECRET_KEY ), 'incremental' => 1, 'dropbox' => 1 );
		}

		return array( 'secret_key' => get_option( AI1WM_SECRET_KEY ), 'dropbox' => 1 );
	}

	public function set_token( $token ) {
		return update_option( 'ai1wmde_dropbox_token', $token );
	}

	public function get_token() {
		return get_option( 'ai1wmde_dropbox_token', false );
	}

	public function set_ssl( $mode ) {
		return update_option( 'ai1wmde_dropbox_ssl', $mode );
	}

	public function get_ssl() {
		return get_option( 'ai1wmde_dropbox_ssl', false );
	}

	public function set_backups( $number ) {
		return update_option( 'ai1wmde_dropbox_backups', $number );
	}

	public function get_backups() {
		return get_option( 'ai1wmde_dropbox_backups', false );
	}

	public function set_total( $size ) {
		return update_option( 'ai1wmde_dropbox_total', $size );
	}

	public function get_total() {
		return get_option( 'ai1wmde_dropbox_total', false );
	}

	public function set_days( $days ) {
		return update_option( 'ai1wmde_dropbox_days', $days );
	}

	public function get_days() {
		return get_option( 'ai1wmde_dropbox_days', false );
	}

	public function set_folder_path( $folder_path ) {
		return update_option( 'ai1wmde_dropbox_folder_path', $folder_path );
	}

	public function get_folder_path() {
		return get_option( 'ai1wmde_dropbox_folder_path', false );
	}

	public function set_folder_shared_link( $folder_shared_link ) {
		return update_option( 'ai1wmde_dropbox_folder_shared_link', $folder_shared_link );
	}

	public function get_folder_shared_link() {
		return get_option( 'ai1wmde_dropbox_folder_shared_link', false );
	}

	public function set_file_chunk_size( $file_chunk_size ) {
		return update_option( 'ai1wmde_dropbox_file_chunk_size', $file_chunk_size );
	}

	public function get_file_chunk_size() {
		return get_option( 'ai1wmde_dropbox_file_chunk_size', false );
	}

	public function set_notify_ok_toggle( $toggle ) {
		return update_option( 'ai1wmde_dropbox_notify_toggle', $toggle );
	}

	public function get_notify_ok_toggle() {
		return get_option( 'ai1wmde_dropbox_notify_toggle', false );
	}

	public function set_notify_error_toggle( $toggle ) {
		return update_option( 'ai1wmde_dropbox_notify_error_toggle', $toggle );
	}

	public function get_notify_error_toggle() {
		return get_option( 'ai1wmde_dropbox_notify_error_toggle', false );
	}

	public function set_notify_error_subject( $subject ) {
		return update_option( 'ai1wmde_dropbox_notify_error_subject', $subject );
	}

	public function get_notify_error_subject() {
		return get_option( 'ai1wmde_dropbox_notify_error_subject', sprintf( __( '❌ Backup to Dropbox has failed (%s)', AI1WMDE_PLUGIN_NAME ), parse_url( site_url(), PHP_URL_HOST ) . parse_url( site_url(), PHP_URL_PATH ) ) );
	}

	public function set_notify_email( $email ) {
		return update_option( 'ai1wmde_dropbox_notify_email', $email );
	}

	public function get_notify_email() {
		return get_option( 'ai1wmde_dropbox_notify_email', false );
	}

	public function set_incremental( $incremental ) {
		return update_option( 'ai1wmde_dropbox_incremental', $incremental );
	}

	public function get_incremental() {
		return get_option( 'ai1wmde_dropbox_incremental', false );
	}
}

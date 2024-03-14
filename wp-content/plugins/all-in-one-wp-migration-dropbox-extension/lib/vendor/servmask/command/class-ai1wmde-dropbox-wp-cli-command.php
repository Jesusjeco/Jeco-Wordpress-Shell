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

if ( defined( 'WP_CLI' ) && class_exists( 'Ai1wm_Backup_WP_CLI_Base' ) ) {
	class Ai1wmde_Dropbox_WP_CLI_Command extends Ai1wm_Backup_WP_CLI_Base {
		public function __construct() {
			parent::__construct();

			if ( ! get_option( 'ai1wmde_dropbox_token', false ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'In order to use All-in-One WP Migration Dropbox extension you need to configure it first.', AI1WMDE_PLUGIN_NAME ),
						__( 'Please navigate to WP Admin > All-in-One WP Migration > Dropbox Settings and Link your Dropbox account.', AI1WMDE_PLUGIN_NAME ),
					)
				);
				exit;
			}
		}

		/**
		 * Creates a new backup and uploads to Dropbox.
		 *
		 * ## OPTIONS
		 *
		 * [--sites[=<comma_separated_ids>]]
		 * : Export sites by id (Multisite only). To list sites use: wp site list --fields=blog_id,url
		 *
		 * [--password[=<password>]]
		 * : Encrypt backup with password
		 *
		 * [--exclude-spam-comments]
		 * : Do not export spam comments
		 *
		 * [--exclude-post-revisions]
		 * : Do not export post revisions
		 *
		 * [--exclude-media]
		 * : Do not export media library (files)
		 *
		 * [--exclude-themes]
		 * : Do not export themes (files)
		 *
		 * [--exclude-inactive-themes]
		 * : Do not export inactive themes (files)
		 *
		 * [--exclude-muplugins]
		 * : Do not export must-use plugins (files)
		 *
		 * [--exclude-plugins]
		 * : Do not export plugins (files)
		 *
		 * [--exclude-inactive-plugins]
		 * : Do not export inactive plugins (files)
		 *
		 * [--exclude-cache]
		 * : Do not export cache (files)
		 *
		 * [--exclude-database]
		 * : Do not export database (sql)
		 *
		 * [--exclude-tables[=<comma_separated_names>]]
		 * : Do not export selected database tables (sql)
		 *
		 * [--exclude-email-replace]
		 * : Do not replace email domain (sql)
		 *
		 * [--replace]
		 * : Find and replace text in the database
		 *
		 * [<find>...]
		 * : A string to search for within the database
		 *
		 * [<replace>...]
		 * : Replace instances of the first string with this new string
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm dropbox backup --replace "wp" "WordPress"
		 * Backup in progress...
		 * Dropbox: Uploading wordpress-20181109-092410-450.wpress (17 MB) [29% complete]
		 * Dropbox: Uploading wordpress-20181109-092410-450.wpress (17 MB) [59% complete]
		 * Dropbox: Uploading wordpress-20181109-092410-450.wpress (17 MB) [89% complete]
		 * Dropbox: Uploading wordpress-20181109-092410-450.wpress (17 MB) [100% complete]
		 * Backup complete.
		 * Backup file: wordpress-20181109-082635-610.wpress
		 * Backup location: https://www.dropbox.com/home/Apps/All%20in%20One%20WP%20Migration/backups/wordpress-20181109-082635-610.wpress
		 * @subcommand backup
		 */
		public function backup( $args = array(), $assoc_args = array() ) {
			$this->run_backup(
				$this->build_export_params( $args, $assoc_args )
			);

			if ( $shared_link = get_option( 'ai1wmde_dropbox_folder_shared_link', false ) ) {
				WP_CLI::log( sprintf( __( 'Backup location: %s', AI1WMDE_PLUGIN_NAME ), $shared_link ) );
			}
		}

		/**
		 * Get a list of Dropbox backup files.
		 *
		 * ## OPTIONS
		 *
		 * [--folder-path=<path>]
		 * : List backups in a specific dropbox subfolder
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm dropbox list-backups
		 * +------------------------------------------------+--------------+-----------+
		 * | Backup name                                    | Date created | Size      |
		 * +------------------------------------------------+--------------+-----------+
		 * | migration-wp-20170908-152313-435.wpress        | 4 days ago   | 536.77 MB |
		 * | migration-wp-20170908-152103-603.wpress        | 4 days ago   | 536.77 MB |
		 * | migration-wp-20170908-152036-162.wpress        | 4 days ago   | 536.77 MB |
		 * +------------------------------------------------+--------------+-----------+
		 *
		 * $ wp ai1wm dropbox list-backups --folder-path=/backups/daily
		 * +------------------------------------------------+--------------+-----------+
		 * | Backup name                                    | Date created | Size      |
		 * +------------------------------------------------+--------------+-----------+
		 * | migration-wp-20170908-152313-435.wpress        | 4 days ago   | 536.77 MB |
		 * | migration-wp-20170908-152103-603.wpress        | 4 days ago   | 536.77 MB |
		 * +------------------------------------------------+--------------+-----------+
		 *
		 * @subcommand list-backups
		 */
		public function list_backups( $args = array(), $assoc_args = array() ) {
			$backups = new cli\Table;

			$backups->setHeaders(
				array(
					'name' => __( 'Backup name', AI1WMDE_PLUGIN_NAME ),
					'date' => __( 'Date created', AI1WMDE_PLUGIN_NAME ),
					'size' => __( 'Size', AI1WMDE_PLUGIN_NAME ),
				)
			);

			$folder_path = $this->get_folder_path( $assoc_args );
			$items       = $this->list_items( $folder_path );

			// Set folder structure
			$response = array( 'items' => array(), 'num_hidden_files' => 0 );

			foreach ( $items as $item ) {
				if ( pathinfo( $item['name'], PATHINFO_EXTENSION ) === 'wpress' ) {
					$backups->addRow(
						array(
							'name' => $item['name'],
							'date' => sprintf( __( '%s ago', AI1WMDE_PLUGIN_NAME ), human_time_diff( $item['date'] ) ),
							'size' => ai1wm_size_format( $item['bytes'], 2 ),
						)
					);
				}
			}

			$backups->display();
		}

		/**
		 * Restores a backup from Dropbox.
		 *
		 * ## OPTIONS
		 *
		 * <file>
		 * : Name of the backup file
		 *
		 * [--folder-path=<path>]
		 * : Download a backup from a specific dropbox subfolder
		 *
		 * [--yes]
		 * : Automatically confirm the restore operation
		 *
		 * ## EXAMPLES
		 *
		 * $ wp ai1wm dropbox restore migration-wp-20170913-095743-931.wpress
		 * Restore in progress...
		 * Restore complete.
		 *
		 * $ wp ai1wm dropbox restore migration-wp-20170913-095743-931.wpress --folder-path=/backups/daily
		 * @subcommand restore
		 */
		public function restore( $args = array(), $assoc_args = array() ) {
			if ( ! isset( $args[0] ) ) {
				WP_CLI::error_multi_line(
					array(
						__( 'A backup name must be provided in order to proceed with the restore process.', AI1WMDE_PLUGIN_NAME ),
						__( 'Example: wp ai1wm dropbox restore migration-wp-20170913-095743-931.wpress', AI1WMDE_PLUGIN_NAME ),
					)
				);
				exit;
			}

			$folder_path = $this->get_folder_path( $assoc_args );
			$items       = $this->list_items( $folder_path );

			$file = null;
			foreach ( $items as $item ) {
				if ( $item['name'] === $args[0] ) {
					$file = $item;
					break;
				}
			}

			if ( is_null( $file ) ) {
				WP_CLI::error_multi_line(
					array(
						__( "The backup file could not be located in $folder_path folder.", AI1WMDE_PLUGIN_NAME ),
						__( 'To list available backups use: wp ai1wm dropbox list-backups', AI1WMDE_PLUGIN_NAME ),
					)
				);
				exit;
			}

			$params = array(
				'archive'    => $args[0],
				'storage'    => ai1wm_storage_folder(),
				'file_path'  => $file['path'],
				'file_size'  => $file['bytes'],
				'cli_args'   => $assoc_args,
				'secret_key' => get_option( AI1WM_SECRET_KEY, false ),
			);

			$this->run_restore( $params );
		}

		/**
		 * Get backup items list
		 *
		 * @param  string $folder_path Folder path where backups located
		 * @return array  Backup items
		 */
		protected function list_items( $folder_path ) {
			$dropbox = new Ai1wmde_Dropbox_Client(
				get_option( 'ai1wmde_dropbox_token', false ),
				get_option( 'ai1wmde_dropbox_ssl', true )
			);

			try {
				$data = $dropbox->list_folder( $folder_path, array( 'folder', 'file' => '/\.wpress$/' ) );
			} catch ( Exception $e ) {
				WP_CLI::error( $e->getMessage() );
				exit;
			}

			$items = array();
			if ( isset( $data['items'] ) ) {
				foreach ( $data['items'] as $item ) {
					$items[] = $item;
				}
			}

			return $items;
		}

		/**
		 * Comparison function for sort by date descending
		 *
		 * @param  array  $a First item to compare
		 * @param  array  $b Second item to compare
		 * @return int    -1/0/1 for less/equal/greater
		 */
		protected function sort_by_date_desc( $a, $b ) {
			if ( $a['date'] === $b['date'] ) {
				return 0;
			}

			return ( $a['date'] > $b['date'] ) ? - 1 : 1;
		}

		/**
		 * Get folder path from command-line or WP settings
		 *
		 * @param  array  $assoc_args CLI params
		 * @return string Folder path
		 */
		protected function get_folder_path( $assoc_args ) {
			if ( isset( $assoc_args['folder-path'] ) ) {
				return sprintf( '/%s', trim( $assoc_args['folder-path'], '/' ) );
			}
			return get_option( 'ai1wmde_dropbox_folder_path', null );
		}
	}
}

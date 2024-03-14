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
?>

<div class="ai1wm-container">
	<div class="ai1wm-row">
		<div class="ai1wm-left">
			<div class="ai1wm-holder" id="ai1wmde-dropbox-connect">
				<h1>
					<?php $token ? _e( 'Dropbox Settings', AI1WMDE_PLUGIN_NAME ) : _e( 'Select Access Level for Dropbox Integration', AI1WMDE_PLUGIN_NAME ); ?>
				</h1>

				<div class="ai1wm-field">
					<?php if ( $token ) : ?>
						<p id="ai1wmde-dropbox-details">
							<?php _e( 'Retrieving Dropbox account details..', AI1WMDE_PLUGIN_NAME ); ?>
						</p>

						<div id="ai1wmde-dropbox-progress">
							<div id="ai1wmde-dropbox-progress-bar"></div>
						</div>

						<p id="ai1wmde-dropbox-space"></p>

						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ai1wmde_dropbox_revoke' ) ); ?>">
							<button type="submit" class="ai1wm-button-red" name="ai1wmde_dropbox_logout" id="ai1wmde-dropbox-logout">
								<i class="ai1wm-icon-exit"></i>
								<?php _e( 'Sign Out from your dropbox account', AI1WMDE_PLUGIN_NAME ); ?>
							</button>
						</form>

					<?php else : ?>
						<dropbox-connect inline-template app-url="<?php echo esc_url( AI1WMDE_REDIRECT_CREATE_URL ); ?>" full-url="<?php echo esc_url( AI1WMDE_REDIRECT_CREATE_FULL_URL ); ?>">
							<div>
								<p><?php _e( 'Before linking your Dropbox account, please choose the desired access level. This determines how the All-in-One WP Migration plugin interacts with your Dropbox:', AI1WMDE_PLUGIN_NAME ); ?></p>

								<form method="post" :action="actionUrl" id="ai1wmde-dropbox-access-form">
									<input type="hidden" name="ai1wmde_client" id="ai1wmde-client" value="<?php echo esc_url( wp_nonce_url( network_admin_url( 'admin.php?page=ai1wmde_settings' ) ) ); ?>" />
									<input type="hidden" name="ai1wmde_purchase_id" id="ai1wmde-purchase-id" value="<?php echo esc_attr( get_option( 'ai1wmde_plugin_key' ) ); ?>" />
									<input type="hidden" name="ai1wmde_site_url" id="ai1wmde-site-url" value="<?php echo esc_attr( site_url() ); ?>" />
									<input type="hidden" name="ai1wmde_admin_email" id="ai1wmde-admin-email" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" />
									<div class="ai1wmde-dropbox-access-level">
										<label for="ai1wmde-dropbox-app-access" class="ai1wmde-dropbox-label" :class="{ 'ai1wmde-dropbox-label-active': type === 'app' }">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M16.3343 20H18.2511C18.7064 20 19.1257 19.7723 19.3533 19.3967L23.8258 12.1109C24.0574 11.7353 24.0574 11.2723 23.8298 10.8929C23.6022 10.5134 23.1829 10.2857 22.7236 10.2857H6.75037C6.29514 10.2857 5.87584 10.5134 5.64822 10.8891L2.91679 15.3364V5.42857C2.91679 5.09464 3.20431 4.82143 3.55572 4.82143H8.24787C8.41559 4.82143 8.57931 4.88594 8.69911 4.99978L9.75734 6.00536C10.5959 6.80223 11.734 7.25 12.92 7.25H17.6122C17.9636 7.25 18.2511 7.52321 18.2511 7.85714V9.07143H20.1679V7.85714C20.1679 6.51763 19.0218 5.42857 17.6122 5.42857H12.92C12.2412 5.42857 11.5903 5.17433 11.1111 4.71897L10.0528 3.7096C9.57365 3.25424 8.92274 3 8.24387 3H3.55572C2.14608 3 1 4.08906 1 5.42857V17.5714C1 18.9109 2.14608 20 3.55572 20H4.50214H16.3343Z" fill="#6495ED"/>
											</svg>

											<span>
												<span class="ai1wmde-dropbox-label-title"><?php _e( 'App Folder Access', AI1WMDE_PLUGIN_NAME ); ?></span>
												<span><?php _e( 'Restricts the plugin\'s access to its own folder in Dropbox. This is a secure option if you prefer the plugin to only manage files within a dedicated space.', AI1WMDE_PLUGIN_NAME ); ?></span>
											</span>

											<input type="radio" v-model="type" class="ai1wmde-dropbox-access-level-select" value="app" id="ai1wmde-dropbox-app-access">
										</label>
										<label for="ai1wmde-dropbox-full-access" class="ai1wmde-dropbox-label" :class="{ 'ai1wmde-dropbox-label-active': type === 'full' }">
											<svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M8.2625 2.63438L4.1375 5.26875L8.2625 7.90312L4.1375 10.5375L0 7.87813L4.13438 5.24375L0 2.63438L4.13438 0L8.2625 2.63438ZM4.1125 11.3656L8.2375 8.73125L12.3625 11.3656L8.2375 14L4.1125 11.3656ZM8.2625 7.87813L12.3875 5.24375L8.2625 2.63125L12.3656 0L16.5 2.63438L12.3656 5.26875L16.5 7.9L12.3656 10.5344L8.2625 7.87813Z" fill="#6495ED"/>
											</svg>

											<span>
												<span class="ai1wmde-dropbox-label-title"><?php _e( 'Full Dropbox Access', AI1WMDE_PLUGIN_NAME ); ?></span>
												<span><?php _e( 'Grants the plugin access to your entire Dropbox account. Choose this if you want the plugin to have the flexibility to manage files across your entire Dropbox.', AI1WMDE_PLUGIN_NAME ); ?></span>
											</span>

											<input type="radio" v-model="type" class="ai1wmde-dropbox-access-level-select" value="full" id="ai1wmde-dropbox-full-access">
										</label>
									</div>

									<button type="submit" class="ai1wmde-button-dropbox-submit" name="ai1wmde_dropbox_link" id="ai1wmde-dropbox-link">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M13.7854 8.61619C15.0709 7.33076 15.0709 5.24905 13.7854 3.96362C12.6479 2.82607 10.8551 2.67819 9.54693 3.61326L9.51052 3.63828C9.18291 3.87262 9.10783 4.32763 9.34217 4.65297C9.5765 4.97831 10.0315 5.05566 10.3569 4.82133L10.3933 4.7963C11.1236 4.27531 12.1223 4.35721 12.7548 4.99196C13.4715 5.70862 13.4715 6.86891 12.7548 7.58557L10.2022 10.1428C9.4855 10.8594 8.3252 10.8594 7.60855 10.1428C6.97379 9.50802 6.89189 8.50926 7.41289 7.78123L7.43791 7.74482C7.67225 7.41721 7.5949 6.96219 7.26956 6.73013C6.94422 6.49807 6.48693 6.57315 6.25487 6.89849L6.22984 6.93489C5.2925 8.2408 5.44038 10.0336 6.57793 11.1711C7.86336 12.4565 9.94507 12.4565 11.2305 11.1711L13.7854 8.61619ZM1.96404 8.08381C0.678613 9.36924 0.678613 11.451 1.96404 12.7364C3.10159 13.8739 4.89436 14.0218 6.20254 13.0867L6.23894 13.0617C6.56655 12.8274 6.64163 12.3724 6.4073 12.047C6.17296 11.7217 5.71794 11.6443 5.39261 11.8787L5.3562 11.9037C4.6259 12.4247 3.62713 12.3428 2.99466 11.708C2.278 10.9891 2.278 9.82881 2.99466 9.11216L5.54731 6.55723C6.26397 5.84057 7.42426 5.84057 8.14092 6.55723C8.77567 7.19198 8.85757 8.19074 8.33658 8.92105L8.31155 8.95745C8.07722 9.28506 8.15457 9.74008 8.47991 9.97214C8.80525 10.2042 9.26254 10.1291 9.4946 9.80378L9.51962 9.76738C10.457 8.4592 10.3091 6.66643 9.17154 5.52888C7.88611 4.24346 5.8044 4.24346 4.51897 5.52888L1.96404 8.08381Z" fill="white"/>
										</svg>
										<?php _e( 'Link with your Dropbox account', AI1WMDE_PLUGIN_NAME ); ?>
									</button>
								</form>
							</div>
						</dropbox-connect>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $token ) : ?>
				<div id="ai1wmde-backups" class="ai1wm-holder">
					<h1><?php _e( 'Dropbox Backups', AI1WMDE_PLUGIN_NAME ); ?></h1>

					<?php if ( Ai1wm_Message::has( 'error' ) ) : ?>
						<div class="ai1wm-message ai1wm-error-message">
							<p><?php echo Ai1wm_Message::get( 'error' ); ?></p>
						</div>
					<?php elseif ( Ai1wm_Message::has( 'success' ) ) : ?>
						<div class="ai1wm-message ai1wm-success-message">
							<p><?php echo Ai1wm_Message::get( 'success' ); ?></p>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ai1wmde_dropbox_settings' ) ); ?>">
						<article class="ai1wmde-article">
							<h3><?php _e( 'Configure your backup plan', AI1WMDE_PLUGIN_NAME ); ?></h3>

							<p>
								<label for="ai1wmde-dropbox-cron-timestamp">
									<?php _e( 'Backup time:', AI1WMDE_PLUGIN_NAME ); ?>
									<input type="text" name="ai1wmde_dropbox_cron_timestamp" id="ai1wmde-dropbox-cron-timestamp" value="<?php echo esc_attr( get_date_from_gmt( date( 'Y-m-d H:i:s', $dropbox_cron_timestamp ), 'g:i a' ) ); ?>" autocomplete="off" />
									<code><?php echo ai1wm_get_timezone_string(); ?></code>
								</label>
							</p>

							<ul id="ai1wmde-dropbox-cron">
								<li>
									<label for="ai1wmde-dropbox-cron-hourly">
										<input type="checkbox" name="ai1wmde_dropbox_cron[]" id="ai1wmde-dropbox-cron-hourly" value="hourly" <?php echo in_array( 'hourly', $dropbox_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every hour', AI1WMDE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmde-dropbox-cron-daily">
										<input type="checkbox" name="ai1wmde_dropbox_cron[]" id="ai1wmde-dropbox-cron-daily" value="daily" <?php echo in_array( 'daily', $dropbox_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every day', AI1WMDE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmde-dropbox-cron-weekly">
										<input type="checkbox" name="ai1wmde_dropbox_cron[]" id="ai1wmde-dropbox-cron-weekly" value="weekly" <?php echo in_array( 'weekly', $dropbox_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every week', AI1WMDE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmde-dropbox-cron-monthly">
										<input type="checkbox" name="ai1wmde_dropbox_cron[]" id="ai1wmde-dropbox-cron-monthly" value="monthly" <?php echo in_array( 'monthly', $dropbox_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every month', AI1WMDE_PLUGIN_NAME ); ?>
									</label>
								</li>
							</ul>

							<p>
								<?php _e( 'Last backup date:', AI1WMDE_PLUGIN_NAME ); ?>
								<strong>
									<?php echo $last_backup_date; ?>
								</strong>
							</p>

							<p>
								<?php _e( 'Next backup date:', AI1WMDE_PLUGIN_NAME ); ?>
								<strong>
									<?php echo $next_backup_date; ?>
								</strong>
							</p>

							<p>
								<label for="ai1wmde-dropbox-incremental">
									<input type="checkbox" name="ai1wmde_dropbox_incremental" id="ai1wmde-dropbox-incremental" value="1" <?php echo empty( $incremental ) ? null : 'checked'; ?> />
									<?php _e( 'Enable incremental backups (optimize backup file size)', AI1WMDE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmde-dropbox-ssl">
									<input type="checkbox" name="ai1wmde_dropbox_ssl" id="ai1wmde-dropbox-ssl" value="1" <?php echo empty( $ssl ) ? 'checked' : null; ?> />
									<?php _e( 'Disable connecting to Dropbox via SSL (only if export is failing)', AI1WMDE_PLUGIN_NAME ); ?>
								</label>
							</p>
						</article>

						<article class="ai1wmde-article">
							<h3><?php _e( 'Destination folder', AI1WMDE_PLUGIN_NAME ); ?></h3>
							<p id="ai1wmde-dropbox-folder-details">
								<span class="spinner" style="visibility: visible;"></span>
								<?php _e( 'Retrieving Dropbox folder details..', AI1WMDE_PLUGIN_NAME ); ?>
							</p>
							<p>
								<input type="hidden" name="ai1wmde_dropbox_folder_path" id="ai1wmde-dropbox-folder-path" />
								<button type="button" class="ai1wm-button-gray" name="ai1wmde_dropbox_change" id="ai1wmde-dropbox-change">
									<i class="ai1wm-icon-folder"></i>
									<?php _e( 'Change', AI1WMDE_PLUGIN_NAME ); ?>
								</button>
							</p>
						</article>

						<article class="ai1wmde-article">
							<h3><?php _e( 'Notification settings', AI1WMDE_PLUGIN_NAME ); ?></h3>
							<p>
								<label for="ai1wmde-dropbox-notify-toggle">
									<input type="checkbox" id="ai1wmde-dropbox-notify-toggle" name="ai1wmde_dropbox_notify_toggle" <?php echo empty( $notify_ok_toggle ) ? null : 'checked'; ?> />
									<?php _e( 'Send an email when a backup is complete', AI1WMDE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmde-dropbox-notify-error-toggle">
									<input type="checkbox" id="ai1wmde-dropbox-notify-error-toggle" name="ai1wmde_dropbox_notify_error_toggle" <?php echo empty( $notify_error_toggle ) ? null : 'checked'; ?> />
									<?php _e( 'Send an email if a backup fails', AI1WMDE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmde-dropbox-notify-email">
									<?php _e( 'Email address', AI1WMDE_PLUGIN_NAME ); ?>
									<br />
									<input class="ai1wmde-email" style="width: 15rem;" type="email" id="ai1wmde-dropbox-notify-email" name="ai1wmde_dropbox_notify_email" value="<?php echo esc_attr( $notify_email ); ?>" />
								</label>
							</p>
						</article>

						<article class="ai1wmde-article">
							<h3><?php _e( 'Retention settings', AI1WMDE_PLUGIN_NAME ); ?></h3>
							<p>
								<div class="ai1wm-field">
									<label for="ai1wmde-dropbox-backups">
										<?php _e( 'Keep the most recent', AI1WMDE_PLUGIN_NAME ); ?>
										<input style="width: 4.5em;" type="number" min="0" name="ai1wmde_dropbox_backups" id="ai1wmde-dropbox-backups" value="<?php echo intval( $backups ); ?>" />
									</label>
									<?php _e( 'backups. <small>Default: <strong>0</strong> unlimited</small>', AI1WMDE_PLUGIN_NAME ); ?>
								</div>

								<div class="ai1wm-field">
									<label for="ai1wmde-dropbox-total">
										<?php _e( 'Limit the total size of backups to', AI1WMDE_PLUGIN_NAME ); ?>
										<input style="width: 4.5em;" type="number" min="0" name="ai1wmde_dropbox_total" id="ai1wmde-dropbox-total" value="<?php echo intval( $total ); ?>" />
									</label>
									<select style="margin-top: -2px;" name="ai1wmde_dropbox_total_unit" id="ai1wmde-dropbox-total-unit">
										<option value="MB" <?php echo strpos( $total, 'MB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'MB', AI1WMDE_PLUGIN_NAME ); ?></option>
										<option value="GB" <?php echo strpos( $total, 'GB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'GB', AI1WMDE_PLUGIN_NAME ); ?></option>
									</select>
									<?php _e( '<small>Default: <strong>0</strong> unlimited</small>', AI1WMDE_PLUGIN_NAME ); ?>
								</div>

								<div class="ai1wm-field">
									<label for="ai1wmde-dropbox-days">
										<?php _e( 'Remove backups older than ', AI1WMDE_PLUGIN_NAME ); ?>
										<input style="width: 4.5em;" type="number" min="0" name="ai1wmde_dropbox_days" id="ai1wmde-dropbox-days" value="<?php echo intval( $days ); ?>" />
									</label>
									<?php _e( 'days. <small>Default: <strong>0</strong> off</small>', AI1WMDE_PLUGIN_NAME ); ?>
								</div>
							</p>
						</article>

						<article class="ai1wmde-article">
							<h3><?php _e( 'Transfer settings', AI1WMDE_PLUGIN_NAME ); ?></h3>
							<div class="ai1wm-field">
								<label><?php _e( 'Slow Internet (Home)', AI1WMDE_PLUGIN_NAME ); ?></label>
								<input name="ai1wmde_dropbox_file_chunk_size" min="4194304" max="20971520" step="4194304" type="range" value="<?php echo $file_chunk_size; ?>" id="ai1wmde-dropbox-file-chunk-size" />
								<label><?php _e( 'Fast Internet (Internet Servers)', AI1WMDE_PLUGIN_NAME ); ?></label>
							</div>
						</article>

						<p>
							<button type="submit" class="ai1wm-button-blue" name="ai1wmde_dropbox_update" id="ai1wmde-dropbox-update">
								<i class="ai1wm-icon-database"></i>
								<?php _e( 'Update', AI1WMDE_PLUGIN_NAME ); ?>
							</button>
						</p>
					</form>
				</div>
			<?php endif; ?>

			<?php do_action( 'ai1wmde_settings_left_end' ); ?>

		</div>
		<div class="ai1wm-right">
			<div class="ai1wm-sidebar">
				<div class="ai1wm-segment">
					<?php if ( ! AI1WM_DEBUG ) : ?>
						<?php include AI1WM_TEMPLATES_PATH . '/common/share-buttons.php'; ?>
					<?php endif; ?>

					<h2><?php _e( 'Leave Feedback', AI1WMDE_PLUGIN_NAME ); ?></h2>

					<?php include AI1WM_TEMPLATES_PATH . '/common/leave-feedback.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
/*
Plugin Name: Jeco auto IP Blacklist
Description: Automatically updates .htaccess with the latest IP blacklist to block spam bots.
Version: 1.0
Author: Jesus Carrero
*/

// Hook to register cron job on plugin activation
function wpcron_activation() {
    if (!wp_next_scheduled('jeco_auto_ip_blacklist')) {
        wp_schedule_event(strtotime('00:00:00'), 'daily', 'jeco_auto_ip_blacklist');
    }
}
register_activation_hook(__FILE__, 'wpcron_activation');

// Hook to clear cron job on plugin deactivation
function wpcron_deactivation() {
    $timestamp = wp_next_scheduled('jeco_auto_ip_blacklist');
    wp_unschedule_event($timestamp, 'jeco_auto_ip_blacklist');
}
register_deactivation_hook(__FILE__, 'wpcron_deactivation');

// Function to handle the IP blacklist update
function jeco_auto_ip_blacklist() {
    if (!defined('DOING_CRON')) return;

    // Include the script file
    require_once(plugin_dir_path(__FILE__) . 'phpscript_httaccess_wordpress.php');
}
add_action('jeco_auto_ip_blacklist', 'jeco_auto_ip_blacklist');
?>

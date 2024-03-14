<?php

function wpcron_activation()
{
    if (!wp_next_scheduled('auto_ip_blacklist')) {
        wp_schedule_event(strtotime('00:00:00'), 'daily', 'auto_ip_blacklist');
    }
}
add_action('admin_init', 'wpcron_activation');

function auto_ip_blacklist()
{
    if (!defined('DOING_CRON')) return;
    require_once('phpscript_httaccess_wordpress.php');
}
add_action('auto_ip_blacklist', 'auto_ip_blacklist');

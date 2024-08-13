<?php
if (getenv('WP_ENV') !== 'local') {
  function disable_plugin_updates()
  {
    echo "In production mode";
    if (current_user_can('manage_options')) {
      remove_action('admin_init', '_maybe_update_plugins');
      remove_action('load-update-core.php', 'wp_update_plugins');
      remove_action('wp_update_plugins', 'wp_update_plugins');
      remove_action('admin_notices', 'update_nag', 3);
    }
  }
  add_action('admin_init', 'disable_plugin_updates');

  // Check if WP_ENV is set to 'local'
  if (getenv('WP_ENV') === 'local') {
    // Set WP_DEBUG to true for local environment
    define('WP_DEBUG', true); // Enable debug mode
    define('WP_DEBUG_LOG', true);
    define('WP_DEBUG_DISPLAY', true);
    @ini_set('display_errors', 1);
  } else {
    // Production settings
    define('WP_DEBUG', false); // Disable debug mode
    define('DISALLOW_FILE_EDIT', true); // Disable file editing from the admin dashboard
    define('DISALLOW_FILE_MODS', true); // Disable plugin and theme updates
  }
}
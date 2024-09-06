<?php
function jeco_install_plugins_on_theme_activation()
{
  // Ensure admin functions are available
  if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
  }

  include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
  include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
  include_once(ABSPATH . 'wp-admin/includes/file.php');
  include_once(ABSPATH . 'wp-admin/includes/misc.php');

  $plugin_slugs = [
    "acf-extended",
    "all-in-one-wp-migration",
    "all-in-one-wp-security-and-firewall",
    "disable-comments",
    "duplicate-page",
    "ewww-image-optimizer",
    "svg-support",
    "wp-optimize"
  ];

  foreach ($plugin_slugs as $slug) {
    $api = plugins_api('plugin_information', array(
      'slug' => $slug,
      'fields' => array(
        'sections' => false
      )
    ));

    if (is_wp_error($api)) {
      error_log("Error: Could not retrieve plugin information for: $slug");
      continue;
    }

    // Check if download link exists
    if (!isset($api->download_link)) {
      error_log("Error: No download link found for: $slug");
      continue;
    }

    $upgrader = new Plugin_Upgrader();
    $result = $upgrader->install($api->download_link);

    if (is_wp_error($result)) {
      error_log("Error: Failed to install $slug.");
    } else {
      error_log("$slug installed successfully.");
    }
  }
}

add_action('after_switch_theme', 'jeco_install_plugins_on_theme_activation');

<?php

//Remove the wordpress version generatord
remove_action('wp_head', 'wp_generator');

// Removing the admin bar
add_filter('show_admin_bar', '__return_false');

//Add thumbnail support
add_theme_support('post-thumbnails');

//Add excerpt to posts and pages
add_post_type_support('page', 'excerpt');
add_post_type_support('post', 'excerpt');

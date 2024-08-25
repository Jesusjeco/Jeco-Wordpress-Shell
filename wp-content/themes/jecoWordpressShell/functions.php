<?php

include("includes/index.php");

//Remove the wordpress version generatord
remove_action('wp_head', 'wp_generator');

// Removing the admin bar
add_filter('show_admin_bar', '__return_false');

//Add thumbnail support
add_theme_support('post-thumbnails');

//Add excerpt to posts and pages
add_post_type_support('page', 'excerpt');
add_post_type_support('post', 'excerpt');

/*
 * Register my Menu
 */
function register_my_menus()
{
    register_nav_menus(
        array(
            'main-menu' => __('Main menu'),
        )
    );
}
add_action('init', 'register_my_menus');

/**
 * Enqueues stylesheets conditionally based on the current page being viewed.
 *
 * The function checks various WordPress conditions to determine the appropriate stylesheet
 * to enqueue for the current page. It ensures that only one stylesheet is enqueued per page 
 * by using return statements after each condition.
 * 
 * This conditions must be set according to the Wordpress hierarchy.
 * 
 * Additionally, a default stylesheet (`default.css`) is always enqueued for all pages.
 *
 * Stylesheets are enqueued for:
 * - Default stylesheet for all pages (`default.css`)
 * - Front page (`front-page.php`)
 * - Custom page template (`hello-world.php`)
 * - Single post (`single-post.php`)
 * - Custom post type archive (`cpt-hello-world`)
 * - Archive pages (`archive.php`)
 * - Single template (`single.php`)
 * - Default page template (`page.php`)
 * - Blog home page (`home.php`)
 *
 * @return void
 */
function jeco_enqueue_styles()
{
  // Always load default.css
  wp_enqueue_style('default-style', STYLES_PATH . 'default.css');

  // Loading in front-page.php
  if (is_front_page()) {
    wp_enqueue_style('front-page-style', STYLES_PATH . 'front-page.css');
    return;
  }

  //Loading when a page uses the hello-world.php template
  if (is_page_template('templates/hello-world.php')) {
    wp_enqueue_style('hello-world-style', STYLES_PATH . 'hello-world.css');
    return;
  }

  //Loading in single-post.php file
  if (is_singular('post')) {
    wp_enqueue_style('single-post-style', STYLES_PATH . 'single-post.css');
    return;
  }

  //Loading when the user looks for /cpt-hello-world. Use this structure for your /[custom_post_type] structure
  if (is_post_type_archive('cpt-hello-world')) {
    wp_enqueue_style('archive-cpt-hello-world-style', STYLES_PATH . 'archive-cpt-hello-world.css');
    return;
  }

  // Loading in archive.php
  if (is_archive()) {
    wp_enqueue_style('archive-style', STYLES_PATH . 'archive.css');
    return;
  }

  //Loading in single.php
  if (is_single()) {
    wp_enqueue_style('single-style', STYLES_PATH . 'single.css');
    return;
  }

  //Loading default page template for any page that does not uses another template
  if (is_page()) {
    wp_enqueue_style('page-style', STYLES_PATH . 'page.css');
    return;
  }

  //Loading in home.php
  if (is_home()) {
    wp_enqueue_style('home-style', STYLES_PATH . 'home.css');
    return;
  }
}
add_action('wp_enqueue_scripts', 'jeco_enqueue_styles');

/**
 * Enqueues jQuery in the footer.
 *
 * This function ensures that jQuery is loaded in the footer of the theme,
 * using the minified version provided by WordPress.
 *
 * @return void
 */
function jeco_enqueue_jquery()
{
  // Enqueue jQuery
  wp_enqueue_script('jquery');

  // Move jQuery to the footer
  add_action('wp_enqueue_scripts', function () {
    wp_scripts()->add_data('jquery', 'group', 1);
    wp_scripts()->add_data('jquery', 'position', 1);
  });
}

add_action('wp_enqueue_scripts', 'jeco_enqueue_jquery');

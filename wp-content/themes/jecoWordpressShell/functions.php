<?php

include("includes/index.php");
/*
* NOTE ABOUT THE ENQUEUE.
* I used to do this part the Wordpress way, but I was not able to make the styles been printed in the head tag.
* I needed that to improve the SEO and load the styles first of anything else.
* I was not able to do that the Wordpress way, so I decided to do it manually and print the styles directly in the head tag
* using the link tags and the url of the files.
*/
// /*
// * Registering styles at init
// */
// add_action('init', function () {
//     wp_register_style('style', get_stylesheet_uri());
//     wp_register_style('default', get_template_directory_uri() . '/dist/css/default.css', false, '1.1', 'all');
// });

// /*
// * wp_head hook actions
// */
// add_action('wp_head', function () {
//     wp_enqueue_style('style');
//     wp_enqueue_style('default');
// });
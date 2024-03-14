<?php
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
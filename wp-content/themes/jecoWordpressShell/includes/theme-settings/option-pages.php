<?php
//This option pages will exist only if the ACF has been installed and activated
if (class_exists('ACF')) {
    add_action('acf/init', function () {
        acf_add_options_page(array(
            'page_title' => 'Footer',
            'menu_slug' => 'footer',
            'redirect' => false,
        ));

        acf_add_options_page(array(
            'page_title' => 'Header',
            'menu_slug' => 'header',
            'position' => '',
            'redirect' => false,
        ));
    });
}

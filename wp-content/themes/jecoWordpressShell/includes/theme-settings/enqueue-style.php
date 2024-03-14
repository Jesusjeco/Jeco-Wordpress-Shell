<?php
/*
 * wp_head actions
 */
add_action("get_header", function () {
    wp_enqueue_style('style');
});
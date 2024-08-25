<?php

/**
 * This function checks if an specific block is in use.
 * If it is, it adds the style.css into the head
 * @return void
 */

function jeco_enqueue_conditional_block_styles()
{
  if (has_block('acf/hello-world')) {
    wp_enqueue_style('hello-world-block', BLOCKS_STYLES_PATH . 'hello-world/style.css');
  }
}
add_action('wp_enqueue_scripts', 'jeco_enqueue_conditional_block_styles');

<?php
if (class_exists('ACF')) {
  function jeco_register_acf_blocks()
  {
    // Check if function exists to avoid errors
    if (function_exists('acf_register_block_type')) {

      // Register the hello-world block
      acf_register_block_type([
        'name'              => 'hello-world',
        'title'             => __('Hello World'),
        'description'       => __('A custom block for displaying a Hello World message.'),
        'render_template'   => get_template_directory() . '/blocks/hello-world/render.php',
        'category'          => 'formatting',
        'icon'              => 'admin-site-alt3',
        'keywords'          => array('hello', 'world'),
        'supports'          => array(
          'align' => true,
        ),
      ]);
    }
  }
  // Hook into the ACF init action to register the block
  add_action('acf/init', 'jeco_register_acf_blocks');
}

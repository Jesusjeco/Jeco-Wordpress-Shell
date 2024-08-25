<?php

/**
 * Template name: hello-world
 * Description: this template is a sandbox for further developments.
 * Notice in the function.php file how the stylesheet is added, depending on the template used.
 */
get_header(); ?>

<main class="hello-world" id="main" role="main">
  <h2>hello-world.php Template</h2>
  <?php the_content() ?>
</main>

<?php get_footer();

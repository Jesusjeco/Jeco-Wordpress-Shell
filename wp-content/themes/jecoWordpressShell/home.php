<?php
get_header(); ?>
<h2>This is the blog php template</h2>
<?php

if (have_posts()) {
    while (have_posts()) {
        the_post();
        the_content();
    }

    the_posts_navigation(
        [
            'mid_size' => 2,
            'prev_text' => 'Previous Page',
            'next_text' => 'Next Page',
        ]
    );

    wp_reset_postdata(); // end while
} //end if
else {
    //No content Found
} // end else
get_footer();

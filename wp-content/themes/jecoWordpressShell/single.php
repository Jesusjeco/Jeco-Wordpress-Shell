<?php get_header(); ?>

<main class="single" id="main" role="main">
    <h2>single.php</h2>
    <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        <header class="entry-header">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail('large', array('class' => 'post-thumbnail'));
            }
            ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <div class="entry-meta">
                <?php
                // Display post author, date, and categories
                echo '<span class="posted-on">Posted on ' . get_the_date() . '</span>';
                echo '<span class="byline"> by ' . get_the_author() . '</span>';
                ?>
                <div class="categories">
                    <?php echo 'Categories: ' . get_the_category_list(', '); ?>
                </div>
                <div class="tags">
                    <?php echo 'Tags: ' . get_the_tag_list('', ', '); ?>
                </div>
            </div>
        </header>

        <div class="entry-content">
            <?php
            // Output the content of the post
            the_content();

            // Display pagination for multi-page posts
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'textdomain'),
                'after'  => '</div>',
            ));
            ?>
        </div>

        <footer class="entry-footer">
            <?php
            // Display post meta information or additional content
            if ( is_singular() && get_the_author_meta('description') ) {
                // Display author bio if available
                echo '<div class="author-bio">' . get_the_author_meta('description') . '</div>';
            }
            ?>
        </footer>
    </article>

    <section class="related-posts">
        <h2>Related Posts</h2>
        <?php
        // Query for related posts based on categories or tags
        $related_args = array(
            'category__in' => wp_get_post_categories($post->ID),
            'post__not_in' => array($post->ID),
            'posts_per_page' => 3
        );
        $related_query = new WP_Query($related_args);

        if ($related_query->have_posts()) :
            echo '<ul>';
            while ($related_query->have_posts()) : $related_query->the_post();
                ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        ?>
    </section>

    <section class="comments">
        <?php
        // Display comments if enabled
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        ?>
    </section>
</main>

<?php get_footer(); ?>

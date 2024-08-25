<?php get_header(); ?>

<main class="archive" id="main" role="main">
    <header class="page-header">
        <h1 class="page-title">Archive/h1>
        <h2>archive.php</h2>
    </header>

    <div class="posts-wrapper">
        <?php if (have_posts()) : ?>

            <?php
            // Start the Loop
            while (have_posts()) : the_post();
            ?>
                <article <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
                    <header class="entry-header">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium', array('class' => 'post-thumbnail'));
                        }
                        ?>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h2>
                        <div class="entry-meta">
                            <?php
                            echo '<span class="posted-on">Posted on ' . get_the_date() . '</span>';
                            echo '<span class="byline"> by ' . get_the_author() . '</span>';
                            echo '<div class="categories">' . get_the_category_list(', ') . '</div>';
                            ?>
                        </div>
                    </header>

                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>

                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
                    </footer>
                </article>

            <?php endwhile; ?>

            <nav class="pagination">
                <?php
                // Display pagination links
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Previous', 'textdomain'),
                    'next_text' => __('Next', 'textdomain'),
                ));
                ?>
            </nav>

        <?php else : ?>

            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>

        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
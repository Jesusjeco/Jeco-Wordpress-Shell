<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php is_front_page() ? the_title() : wp_title(''); ?>
    </title>
    <?php wp_head(); ?>
</head>

<body>
    <?php
    $header_data = get_field('header_data', 'option');
    ?>
    <p>Header</p>
    <header>
        <?php clean_custom_menus(); ?>
    </header>

    <!-- Breadcrumbs -->
    <nav class="breadcrumb">
        <!-- Fallback content if breadcrumbs are not available -->
        <div class="breadcrumb-trail">
            <?php
            if (!is_front_page()) {
                echo '<a href="' . home_url() . '">Home</a> / ';
                if (is_category() || is_single()) {
                    the_category(' / ');
                    if (is_single()) {
                        echo " / ";
                        the_title();
                    }
                } elseif (is_page()) {
                    echo the_title();
                }
            }
            ?>
        </div>
    </nav>
    <!-- End Breadcrumbs -->
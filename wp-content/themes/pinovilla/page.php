<?php get_header(); ?>

<main role="main">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            // Remove wpautop to prevent mangling of complex HTML from seeder
            remove_filter('the_content', 'wpautop');
            the_content();
            add_filter('the_content', 'wpautop');
        endwhile;
    endif;
    ?>
</main>

<?php get_footer(); ?>

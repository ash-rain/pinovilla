<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="pino-page-title">
    <div class="auto-container">
        <h1><?php the_title(); ?></h1>
    </div>
</div>

<div class="pino-page-content">
    <div class="auto-container">
        <?php
        remove_filter('the_content', 'wpautop');
        the_content();
        add_filter('the_content', 'wpautop');
        ?>
    </div>
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>

<?php get_header(); ?>

<main role="main">
    <div class="auto-container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
        else :
            _e( 'Sorry, no posts matched your criteria.', 'pinovilla' );
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>

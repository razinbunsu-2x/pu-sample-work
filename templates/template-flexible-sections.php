<?php
/*
Template Name: Flexible Sections
Template Post Type: page
*/

get_header();
?>

<main id="primary" class="site-main single-page default-page new-theme">

  <?php while (have_posts()) : the_post(); ?>

    <?php
    // This will scan all ACF fields for this post, 
    // find flexible content fields, and render all of their layouts.
    render_all_flexible_content();
    ?>

  <?php endwhile; ?>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
?>
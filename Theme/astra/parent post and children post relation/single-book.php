<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<div id="primary" <?php astra_primary_class(); ?>>

	<?php astra_primary_content_top(); ?>

	<?php astra_content_loop(); ?>
	<?php
        $astra_chapter_args = array(
            'post_type'      => 'chapter',
            'post_per_page' => -1,
            'meta_key'       => 'parent_book',
            'meta_value'     => get_the_ID(),
        );
        $astra_chapters = new WP_Query( $astra_chapter_args );
//        echo $astra_chapters->found_posts;
    echo "<h3>";
    _e("Chapters:");
    echo "</h3>";
    while( $astra_chapters->have_posts() ) {
	    $astra_chapters->the_post();
        $astra_chapter_title = get_the_title();
        $astra_chapter_link = get_the_permalink();
        printf("<a href='%s'>%s</a><br>", esc_url( $astra_chapter_link), esc_html( $astra_chapter_title ) );
    }
	?>
	<?php astra_primary_content_bottom(); ?>


</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>

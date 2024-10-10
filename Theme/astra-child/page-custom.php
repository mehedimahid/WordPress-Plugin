<?php
/*
Template Name: Custom_Page
*/
get_header();
?>
<div class="posts text-center">
<?php
    $_p = get_posts(array(
            "post__in"=>array(171,173,87),
        "orderby"=>"post__in",//je vabe post id dibe se vabe rakhte chaile
    ));
    foreach ($_p as $post) {
        setup_postdata($post);
        ?>
        <h2><a href="<?php the_permalink();?>"><?php the_title()?></a></h2>
    <?php
    }
    wp_reset_postdata();
?>
    <div class="container post-pagination">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-8"></div>
        </div>
    </div>
</div>
<?php
get_footer();
?>
<!---->
<!--//--><?php
//	$paged          = get_query_var( "paged" ) ? get_query_var( "paged" ) : 1;
////	$posts_per_page = 2;
////	$post_ids       = array( 173,171 );
////	$_p             = get_posts( array(
////		'posts_per_page' => $posts_per_page,
////		'post__in'       => $post_ids,
////		'orderby'        => 'post__in',
////		'paged'          => $paged
////	) );
////	foreach ( $_p as $post ) {
////		setup_postdata( $post );
////		?>
<!--//        <h2><a href="--><?php //the_permalink(); ?><!--">--><?php //the_title(); ?><!--</a></h2>-->
<!--//        --><?php
////	}
////	wp_reset_postdata();
////	?>
<!--//-->
<!--//-->
<!--<div class="container post-pagination">-->
<!--    //-->
<!--    <div class="row">-->
<!--        //-->
<!--        <div class="col-md-4"></div>-->
<!--        //-->
<!--        <div class="col-md-8">-->
<!--            // --><?php
//			//				echo paginate_links( array(
//			//					'total' => ceil( count( $post_ids ) / $posts_per_page )
//			//				) );
//			//				?>
<!--            //-->
<!--        </div>-->
<!--        //-->
<!--    </div>-->
<!--    //-->
<!--</div>				-->
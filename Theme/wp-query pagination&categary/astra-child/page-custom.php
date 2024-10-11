<?php
/*
Template Name: Custom_Page wp-query
*/
get_header();
?>
<div class="posts text-center">
<?php
    $posts_id =array(1,171,173,87,8);
    $posts_per_page = 2;
    $paged = get_query_var('paged')?get_query_var('paged'):1;
    $_p = new wp_query(array(
            "category_name"=>'more-info',
            "posts_per_page"=>$posts_per_page,
//            "post__in" =>$posts_id,
//            "orderby"  =>"post__in",//je vabe post id dibe se vabe rakhte chaile
            "paged"    =>$paged
    ));
    while ($_p -> have_posts()) {
            $_p -> the_post();
        ?>
        <h2><a href="<?php the_permalink();?>"><?php the_title()?></a></h2>
    <?php
    }
    wp_reset_query();
    ?>
    <div class="container post-pagination">
        <div class="row">
            <div class="col-md-12">
                <?php
                     echo paginate_links( array(
                             "total"=>$_p -> max_num_pages,//max_num_pages ceil(count($posts_id)/$posts_per_page)ai kajtai kore
                             'curent_page'=>$paged,
//                             'prev_next'=>true,//previos and next button show korar jonno
                     ))
                ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>

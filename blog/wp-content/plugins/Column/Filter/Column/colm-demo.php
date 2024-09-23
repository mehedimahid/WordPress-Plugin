<?php
/**
 * Plugin Name: Column
 * Plugin URI:
 * Description: Add your necessary column
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: colm-demo
 * Domain Path: /languages/
 * @package colm-demo
 */

function colm_bootstrap() {
	load_plugin_textdomain('colm-demo', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'colm_bootstrap');

function colm_post_column($column) {
	unset($column['tags']);
	$column['id']        = __('Post ID', 'colm-demo');
	$column['thumbnail'] = __('Thumbnail ', 'colm-demo');
	$column['wordcount'] = __('Word Count ', 'colm-demo');

	return $column;
}

add_filter('manage_posts_columns', "colm_post_column");
add_filter('manage_pages_columns', "colm_post_column");

function colm_manage_posts_id($column_name, $post_id) {
	if ($column_name == 'id') {
		echo $post_id;
	} elseif ($column_name == "thumbnail") {
		$thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
		echo $thumbnail;
	} elseif ($column_name == "wordcount") {
		$wordn = get_post_meta($post_id, 'wordn', true);
		if (empty($wordn)) {
			$_post = get_post($post_id);
			$content = $_post->post_content;
			$wordn = str_word_count(strip_tags($content));
			update_post_meta($post_id, 'wordn', $wordn);
		}
		echo $wordn;
	}
}

add_action("manage_posts_custom_column", "colm_manage_posts_id", 10, 2);
add_action("manage_pages_custom_column", "colm_manage_posts_id", 10, 2);

// Make word count column sortable
function colm_sortable_column($columns) {
	$columns['wordcount'] = 'wordn';

	return $columns;
}

add_filter('manage_edit-post_sortable_columns', 'colm_sortable_column');
add_filter('manage_edit-page_sortable_columns', 'colm_sortable_column');

function colm_set_word_count() {
	$_posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type'      => array( 'post', 'page' ),
		'post_status'    => 'any'
	));

	foreach ($_posts as $p) {
		$content = $p->post_content;
		$wordn = str_word_count(strip_tags($content));
		update_post_meta($p->ID, 'wordn', $wordn);
	}
}

//add_action('init', 'colm_set_word_count');

function colm_set_sort_word_count($query) {
	if (!is_admin()) {
		return;
	}
	$orderby = $query->get('orderby');
//	error_log( print_r( $orderby , true) . "\n\n", 3, __DIR__ . '/log.txt' );

	if ("wordn" == $orderby) {
		$query->set('meta_key', 'wordn');
		$query->set('orderby', 'meta_value_num');
	}
}

add_action("pre_get_posts", "colm_set_sort_word_count");


function colm_set_update_sort_word_count($post_id) {
	$p = get_post($post_id);
	$content = $p->post_content;
	$wordn = str_word_count(strip_tags($content));
	update_post_meta($p->ID, 'wordn', $wordn);
}

add_action("save_post", "colm_set_update_sort_word_count");
//create Filter Option
function colm_posts_filter(){
	//only for post
	if(isset($_GET['post_type']) && $_GET['post_type'] != 'post') {
		return;
	}
	$filter_value = isset($_GET['DEMOFILTER']) ? $_GET['DEMOFILTER'] : '';
	$values = array(
		"0" =>__("Select Status", "colm-demo"),
		"1" =>__("Select posts", "colm-demo"),
		"2" =>__("Lorem Ipsum", "colm-demo")
	)
	?>
	<select name="DEMOFILTER">
		<?php
        foreach ($values as $key=>$value){
            printf("<option value = %s %s>%s</option>",
            $key,
            $key == $filter_value? "selected":'',
            $value
            );
        }
        ?>
	</select>
	<?php
}
add_action("restrict_manage_posts", "colm_posts_filter");
//Show filtered value
function colm_filter_data($query){
	if(!is_admin()){
		return;
	}
	$filter_value = isset($_GET['DEMOFILTER']) ? $_GET['DEMOFILTER'] : '';
	if($filter_value == '1') {
		$query->set('post__in', array(1,26, 87));
	}elseif($filter_value == '2') {
		$query->set('post__in', array(8,171,173));
	}
}
add_action("pre_get_posts", "colm_filter_data");






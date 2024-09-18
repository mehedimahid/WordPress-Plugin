<?php
/**
 * Plugin Name: Column
 * Plugin URI:
 * Description:Add your necessary column
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: colm-demo
 * Domain Path:/languages/
 * @package colm-demo
 */

function colm_bootstrap(){
	load_plugin_textdomain('colm-demo', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'colm_bootstrap');

function colm_post_column($column){
	unset($column['tags']);
	$column['id']= __('Post ID','colm-demo');
	$column['thumbnail']= __('Thumbnail ','colm-demo');
	return $column;
}
add_filter('manage_posts_columns', "colm_post_column");
add_filter('manage_pages_columns', "colm_post_column");

function colm_manage_posts_id($column_name,$post_id){
	if($column_name=='id'){
		echo $post_id;
	}elseif ($column_name =="thumbnail"){
		$thumbnail = get_the_post_thumbnail($post_id,array(80,80));
		echo $thumbnail;
	}
}
add_action("manage_posts_custom_column", "colm_manage_posts_id",10,2 );
add_action("manage_pages_custom_column", "colm_manage_posts_id",10,2 );


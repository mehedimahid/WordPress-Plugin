<?php
/**
 * Plugin Name: Custom Post Demo
 * Plugin URI:
 * Description:
 * Requires at least:
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI:
 * License:  GPLv2 or later
 * License URI:
 * Text Domain: custompost_demo
 * Domain Path: /languages/
 * @package
 */

function cpde_custom_post_demo(){
	$label = array(
		'name'=>__('Recipes', 'custompost_demo'),
		'singular_name'=>__('Recipe', 'custompost_demo'),
		'all_item'=>__('My Recipe', 'custompost_demo'),
		'add_new'=>__('New Recipe', 'custompost_demo'),
	);
	$args = array(
		'labels'=>$label,
		'public'=>true,
		'publicly_queryable'=>true,
		'has_archive'=>'recipes',
		'show_ui'=>true,
		'show_in_nav_menus'=>true,
		'show_in_rest'=>true,
		'rest_base'=>'',
		'menu_position'=>6,
		'hierarchical'=>false,
		'capability_type'=>'post',
		'map_meta_cap'=>true,
		'supports'=>array('title','editor','thumbnail','excerpt','comments'),
		'taxonomies'=>array('category'),


	);
	register_post_type('recipes', $args);
}
add_action('init','cpde_custom_post_demo');

function cpde_single_recipe_template($file){
	global $post;
	if('recipes'==$post->post_type){
		$file_path = plugin_dir_path(__FILE__).'cpt-template/single-recipe.php';
		$file = $file_path;
	}
	return $file;
}
add_filter('single_template','cpde_single_recipe_template');


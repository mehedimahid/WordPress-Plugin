<?php
/**
 * Plugin Name: PopUp Creator
 * Plugin URI: 
 * Description: 
 * Requires at least: 
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI: 
 * License:  GPLv2 or later
 * License URI: 
 * Text Domain: popupcreator
 * Domain Path: /languages/
 * @package 
 */
class PopUpCreator {
	function __construct() {
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
		add_action('init', array($this, 'register_popup_creator'));
	}
	function load_plugin_textdomain() {
		load_plugin_textdomain('popupcreator', FALSE, basename(dirname(__FILE__)) . '/languages');
	}
	function register_popup_creator() {
		$labels = array(
			'name' => __('Popups', 'popupcreator'),
			'singular_name' => __('Popup', 'popupcreator'),
			'featured_image' => __('Popup Image', 'popupcreator'),
			'set_featured_image' => __('Set Popup Image', 'popupcreator'),
		);
		$args = array(
			'label'=>__('Popups', 'popupcreator'),
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'has_archive' => false,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'delete_with_user'=>false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'query_var' => true,
			'map_meta_cap' => true,
			'rewrite' => array( 'slug' => 'popup', 'with_front' => false ),
			'supports'=>array('title','thumbnail')
		);
		register_post_type('popup', $args);
	}
}
new PopUpCreator;


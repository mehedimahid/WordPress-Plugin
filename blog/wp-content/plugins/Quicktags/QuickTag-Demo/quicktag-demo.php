<?php
/**
 * Plugin Name:QuickTag Demo
 * Plugin URI: 
 * Description: 
 * Requires at least: 
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI: 
 * License:  GPLv2 or later
 * License URI: 
 * Text Domain: quicktags-demo
 * Domain Path: /languages/
 * @package 
 */

function qtsd_assets($screen){
	if('post.php' == $screen){
		wp_enqueue_script( 'qtsd-main-js', plugin_dir_url( __FILE__ ) . "/assets/js/quicktags.js", array('quicktags') );

	}
}
add_action('admin_enqueue_scripts', 'qtsd_assets');


<?php
/**
 * Plugin Name: bangla font
 * Plugin URI:
 * Description: write in bangla
 * Requires at least:
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI:
 * License:  GPLv2 or later
 * License URI:
 * Text Domain: 'banglafont'
 * Domain Path: /languages/
 * @package
 */

define('BNPHVERSION','1.0.1');
function bnph_admin_assets($screen){
	if('post-new.php' == $screen || 'post.php' == $screen){

		wp_enqueue_script('bnph-phonetic-driver-js',plugin_dir_url(__FILE__)."assets/js/phonetic.driver.js",null,BNPHVERSION,true);
		wp_enqueue_script('bnph-phonetic-engine-js',plugin_dir_url(__FILE__)."assets/js/engine.js",array('jquery'),BNPHVERSION,true);
		wp_enqueue_script('bnph-phonetic-qt-js',plugin_dir_url(__FILE__)."assets/js/qt.js",array('jquery','quicktags'),BNPHVERSION,true);
	}
}
add_action('admin_enqueue_scripts','bnph_admin_assets');
<?php
/**
 * Plugin Name: Demo Widgets
 * Plugin URI:
 * Description:
 * Requires at least:
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI:
 * License:  GPLv2 or later
 * License URI:
 * Text Domain: demowidgets
 * Domain Path: /languages/
 * @package
 */
require_once( plugin_dir_path( __FILE__ ) . 'widgets/widgets.php' );
require_once( plugin_dir_path( __FILE__ ) . 'widgets/advertisementwidget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'widgets/demowidgetsui.php' );

function demo_widgets_domain_load(){
	load_plugin_textdomain('demowidgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
}
add_action('plugins_loaded', 'demo_widgets_domain_load');

function demo_widgets_register() {
	register_widget('DemoWidgets');
	register_widget('DemoWidgetsUI');
	register_widget('AdvertisementWidget');
}
add_action('widgets_init', 'demo_widgets_register');
function demowidget_admin_enqueue_scripts($screen){
	if($screen=="widgets.php") {
		wp_enqueue_media();
		wp_enqueue_script("advertisement-widget-js", plugin_dir_url(__FILE__)."js/media-gallery.js", array("jquery"), "1.0", 1);
		wp_enqueue_style("demowidget-admin-ui-css",plugin_dir_url(__FILE__)."css/widget.css");
	}
}

add_action("admin_enqueue_scripts","demowidget_admin_enqueue_scripts");
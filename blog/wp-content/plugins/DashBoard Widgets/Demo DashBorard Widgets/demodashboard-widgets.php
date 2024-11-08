<?php
/**
 * Plugin Name: Demo Dashboard Widgets
 * Plugin URI: 
 * Description: 
 * Requires at least: 
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI: 
 * License:  GPLv2 or later
 * License URI: 
 * Text Domain: demodashboard-widgets
 * Domain Path: /languages/
 * @package 
 */

function demodashboard_widgets_load_textdomain() {
	load_plugin_textdomain('demodashboard-widgets',false,plugin_basename( dirname( __FILE__ )."/languages" ));
}
add_action('plugins_loaded','demodashboard_widgets_load_textdomain');

function demodashboard_widgets_dashboard_setup(){
	wp_add_dashboard_widget('demodashboardwodgets',
	__('Dashboard Widgets', 'demodashboard-widgets'),
	'demodashboard_widgets_display'
	);
}
add_action('wp_dashboard_setup','demodashboard_widgets_dashboard_setup');


function demodashboard_widgets_display(){
	$feeds = array(
		array(
			'url'=>'https://wptavern.com/feed',
			'show_author'=>false,
			'show_date'=>true,
			'show_summary'=>false,
		)
	);
	wp_dashboard_primary_output('demodashboardwodgets',$feeds);
}
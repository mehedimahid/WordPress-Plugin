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
require_once(plugin_dir_path( __FILE__ ) . 'widgets/class.class.widgets.php');
function demo_widgets_domain_load(){
	load_plugin_textdomain('demowidgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
}
add_action('plugins_loaded', 'demo_widgets_domain_load');

function demo_widgets_register() {
	register_widget('DemoWidgets');
}
add_action('widgets_init', 'demo_widgets_register');

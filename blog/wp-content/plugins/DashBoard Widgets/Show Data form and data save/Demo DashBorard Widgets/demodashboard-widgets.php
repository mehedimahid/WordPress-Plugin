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
	if(current_user_can('edit_dashboard')){
		wp_add_dashboard_widget('demodashboardwodgets',
			__('Dashboard Widgets', 'demodashboard-widgets'),
			'demodashboard_widgets_display_output',
			'demodashboard_widgets_display_output_configue'
		);
	}else{
		wp_add_dashboard_widget('demodashboardwodgets',
			__('Dashboard Widgets', 'demodashboard-widgets'),
			'demodashboard_widgets_display_output'
		);
	}
}
add_action('wp_dashboard_setup','demodashboard_widgets_dashboard_setup');


function demodashboard_widgets_display_output(){
	$number_of_posts = get_option('demodashboardwodgets_nop', 5);

	$feeds = array(
		array(
			'url'=>'https://wptavern.com/feed',
			'items'=>$number_of_posts,
			'show_author'=>false,
			'show_date'=>true,
			'show_summary'=>false,
		)
	);
	wp_dashboard_primary_output('demodashboardwodgets',$feeds);
}

function demodashboard_widgets_display_output_configue() {
	$number_of_posts = get_option('demodashboardwodgets_nop', 5);
	if(isset($_POST['dashboard-widget-nonce'])&& wp_verify_nonce($_POST['dashboard-widget-nonce'], 'edit-dashboard-widget_demodashboardwodgets')) {
		if ( isset( $_POST['demodashboardwodgets_nop'] ) && $_POST['demodashboardwodgets_nop'] > 0 ) {
			$number_of_posts = sanitize_text_field( $_POST['demodashboardwodgets_nop'] );
			update_option( 'demodashboardwodgets_nop', $number_of_posts );
		}
	}
	?><p>
		<label>Number of Posts:</label><br/>
		<input type="text" class="widefat" name="demodashboardwodgets_nop" id="demodashboardwodgets_nop" value="<?php echo $number_of_posts; ?>">
		</p>
	<?php
}






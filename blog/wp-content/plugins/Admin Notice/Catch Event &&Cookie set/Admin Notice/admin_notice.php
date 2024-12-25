<?php

/**
 * Plugin Name: Admin Notice
 * Plugin URI:
 * Description: Notice from Admin
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 *Text Domain: admin_notice
 *Domain Path: /languages/
 */

function an_admin_notice(){
    if(!(isset($_COOKIE["admin_cookie"])&& $_COOKIE['admin_cookie'] == 1)){
	?>
	<div id="an_admin_notice" class="notice notice-success is-dismissible">
		<p>Hey, some information for you </p>
	</div>
<?php
    }
}
add_action('admin_notices', 'an_admin_notice');

add_action('admin_enqueue_scripts',function(){
	wp_enqueue_script('an_notice_script', plugin_dir_url( __FILE__ ) . 'assets/js/index.js',array('jquery'),time());
});



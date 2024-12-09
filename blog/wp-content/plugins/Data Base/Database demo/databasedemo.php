<?php
/**
 * Plugin Name: DataBase Demo
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 *Text Domain: languages
 *Domain Path: /languages/
 */

function databasedemo_activate(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	$sql = "CREATE TABLE $table_name (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) ,
    email VARCHAR(250) ,
    PRIMARY KEY (id)
);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

}
register_activation_hook( __FILE__, 'databasedemo_activate' );


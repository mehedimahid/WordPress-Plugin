<?php
/**
 * Plugin Name: DataBase Demo
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 *Text Domain: dbd
 *Domain Path: /languages/
 */

define('DBD_VERSION','1.2');
function dbd_demo_activate(){
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
	add_option('dbd_db_version',DBD_VERSION);
	if(get_option('dbd_db_version') != DBD_VERSION){
		$sql = "CREATE TABLE $table_name (
		    id INT NOT NULL AUTO_INCREMENT,
		    name VARCHAR(250) ,
		    email VARCHAR(250) ,
		    age INT,
		    PRIMARY KEY (id)
		);";

		dbDelta($sql);//dbDelta new column add korte pare but delete kore pare na
		update_option('dbd_db_version',DBD_VERSION);
	}
}
register_activation_hook( __FILE__, 'dbd_demo_activate' );

function dbd_demo_drop_column(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	if(get_option('dbd_db_version') != DBD_VERSION){
		$query = "ALTER TABLE $table_name DROP COLUMN age";//age column delete
		$wpdb->query($query);
	}
	update_option('dbd_db_version',DBD_VERSION);
}
add_action('plugins_loaded', 'dbd_demo_drop_column');

function dbd_demo_insert_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	$wpdb->insert($table_name,[
		'name'=>'John Doe',
		'email'=>'john@doe.com',
	]);
	$wpdb->insert($table_name,[
		'name'=>'Jane Doe',
		'email'=>'jane@doe.com',
	]);
}
register_activation_hook( __FILE__, 'dbd_demo_insert_data' );
function dbd_demo_flush_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	$query="TRUNCATE TABLE $table_name";
	$wpdb->query($query);

}
register_deactivation_hook( __FILE__, 'dbd_demo_flush_data' );

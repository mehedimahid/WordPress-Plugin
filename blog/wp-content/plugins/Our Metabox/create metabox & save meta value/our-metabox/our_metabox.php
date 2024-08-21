<?php
/**
 * Plugin Name: Our MetaBox
 * Plugin URI:
 * Description:Metabox API
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: our-metabox
 * Domain Path:/languages/
 * @package our-metabox
 */
class OurMetaBox {
	public function __construct() {
		add_action("plugins_loaded", array($this, "omb_load_textdomain"));
		add_action("admin_menu", array($this, "omb_admin_menu"));
		add_action("save_post", array($this, "omb_save_post_location"));
	}
	function omb_save_post_location($post_id){

		$location = isset($_POST["omb_location"]) ?($_POST["omb_location"]) : '';
		if($location == ""){
			return $post_id;
		}
		update_post_meta($post_id, "omb_location", $location);
	}
	function omb_admin_menu(){
		add_meta_box(
			"omb_post_location",
			__("Location", "our-metabox"),
			array($this, "omb_post_location_callback"),
			"post"
		);
	}

	function omb_post_location_callback($post){
		$location = get_post_meta($post->ID, "omb_location", true);
		$label = __("Location", "our-metabox");
		$metabox_html = <<<EOD
			<p>
			<label for="omb_location">{$label}</label>
			<input type="text" id="omb_location" name="omb_location" value="{$location}" />
			</p>
		EOD;
		echo $metabox_html;

	}
	function omb_load_textdomain(){
		load_plugin_textdomain('our-metabox', false, basename(dirname(__FILE__)) . '/languages');
	}
}

new OurMetaBox();


























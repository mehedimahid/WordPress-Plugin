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
	function omb_load_textdomain(){
		load_plugin_textdomain('our-metabox', false, basename(dirname(__FILE__)) . '/languages');
	}
	private function is_secured($nonce_field,$action,$post_id){
		$nonce = isset($_POST[$nonce_field]) ?($_POST[$nonce_field]) : '';
		if($nonce==''){
			return false;
		}
		if(!wp_verify_nonce($nonce,$action)){
			return false;
		}
		if(!current_user_can('edit_post',$post_id)){
			return false;
		}
		if(wp_is_post_autosave($post_id)){
			return false;
		}
		if(wp_is_post_revision($post_id)){
			return false;
		}

		return true;

	}
	function omb_save_post_location($post_id){
		if(!$this->is_secured("omb_location_field","omb_location",$post_id)){
			return $post_id;
		}
		$location = isset($_POST["omb_location"]) ?($_POST["omb_location"]) : '';
		$country = isset($_POST["omb_country"]) ?($_POST["omb_country"]) : '';
		$is_favorite = isset($_POST["omb_is_favorite"]) ?($_POST["omb_is_favorite"]) : 0;
		if($location == ""){
			return $post_id;
		}
		if($country== ""){
			return $post_id;
		}
		$location = sanitize_text_field($location);
		$country = sanitize_text_field($country);
		update_post_meta($post_id, "omb_location", $location);
		update_post_meta($post_id, "omb_country", $country);
		update_post_meta($post_id, "omb_is_favorite", $is_favorite);
	}

	function omb_admin_menu(){
		add_meta_box(
			"omb_post_location",
			__("Location", "our-metabox"),
			array($this, "omb_post_location_callback"),
			array("post","page")//where show this meta box
		);
	}

	function omb_post_location_callback($post){
		$location = get_post_meta($post->ID, "omb_location", true);
		$country = get_post_meta($post->ID, "omb_country", true);
		$is_checked = get_post_meta($post->ID, "omb_is_favorite", true);
		$checked = $is_checked ? "checked" : "";
		$label1 = __("Location", "our-metabox");
		$label2 = __("Country", "our-metabox");
		$label3 = __("Is Favorite", "our-metabox");
		wp_nonce_field("omb_location", "omb_location_field");
		$metabox_html = <<<EOD
			<p>
				<label for="omb_location">{$label1}</label>
				<input type="text" id="omb_location" name="omb_location" value="{$location}" />
			</p>
			<p>
				<label for="omb_country">{$label2}</label>
				<input type="text" id="omb_country" name="omb_country" value="{$country}" />
			</p>
			<p>
				<label for="omb_is_favorite">{$label3}</label>
				<input type="checkbox" id="omb_is_favorite" name="omb_is_favorite" value="1" {$checked}/>
			</p>
		EOD;
		echo $metabox_html;

	}

}

new OurMetaBox();


























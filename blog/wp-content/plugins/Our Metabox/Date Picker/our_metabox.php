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
		add_action("admin_enqueue_scripts",array($this, "omb_admin_assets"));
//		add_action("admin_enqueue_scripts",array($this, "omb_admin_assets"));
	}
	function omb_load_textdomain(){
		load_plugin_textdomain('our-metabox', false, basename(dirname(__FILE__)) . '/languages');
	}
	function omb_admin_assets(){
		wp_enqueue_style('omb-admin-style', plugin_dir_url( __FILE__).'assets/admin/css/style.css', null,time());
		wp_enqueue_style('jq-ui-style', '//code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css', null,time());
		wp_enqueue_script('omb-admin-script', plugin_dir_url( __FILE__).'assets/admin/js/main.js', array('jquery','jquery-ui-datepicker'),time(),true);

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
//		$country1 = isset($_POST["omb_country1"]) ?($_POST["omb_country1"]) : '';
		$is_favorite = isset($_POST["omb_is_favorite"]) ?($_POST["omb_is_favorite"]) : 0;
		$colors = isset($_POST["omb_color"]) ?($_POST["omb_color"]) : array();
		$color = isset($_POST["omb_clr"]) ?($_POST["omb_clr"]) : '';
		$country2 = isset($_POST["omb_country"]) ? sanitize_text_field($_POST["omb_country"]) : '';
		if($location == ""){
			return $post_id;
		}
//		if($country1== ""){
//			return $post_id;
//		}
		$location = sanitize_text_field($location);
//		$country1 = sanitize_text_field($country1);
		update_post_meta($post_id, "omb_location", $location);
//		update_post_meta($post_id, "omb_country1", $country1);
		update_post_meta($post_id, "omb_is_favorite", $is_favorite);
		update_post_meta($post_id, "omb_color", $colors);
		update_post_meta($post_id, "omb_clr", $color);
		update_post_meta($post_id, "omb_country", $country2);
	}

	function omb_admin_menu(){
		add_meta_box(
			"omb_post_location",
			__("Location", "our-metabox"),
			array($this, "omb_display_metabox"),
			array("post","page")//where show this meta box
		);
		//Meta Box Styling
		add_meta_box(
			"omb_book_info",
			__("Book Info", "our-metabox"),
			array($this, "omb_book_info"),
			array("book")//where show this meta box
		);
	}
	//Meta Box Styling
	function omb_book_info(){
			wp_nonce_field('omb_book','omb_book_nonce');
			$metabox_html = <<<HTML
				<div class="field">
					<div class="field_c">
						<div class="label_c" >
							<label for="book_author">Book Author:</label>
						</div>
						<div class="input_c"> 
							<input type="text" class="widefat" id="book_author" />
						</div>
						<div class="float_c"></div>
					</div>
					<div class="field_c">
						<div class="label_c" >
							<label for="book_isbn">Book ISBNr:</label>
						</div>
						<div class="input_c">
							<input type="text" id="book_isbn" />
						</div>
						<div class="float_c"></div>
					</div>
					<div class="field_c">
						<div class="label_c" >
							<label for="book_pby">Publish year:</label>
						</div>
						<div class="input_c">
							<input type="text" id="book_pby" class="book_pby" />
						</div>
						<div class="float_c"></div>
					</div>
				</div>
			HTML;
			echo $metabox_html;

	}
	function omb_display_metabox($post){
		$location = get_post_meta($post->ID, "omb_location", true);
//		$country = get_post_meta($post->ID, "omb_country", true);
		$is_checked = get_post_meta($post->ID, "omb_is_favorite", true);
		$save_colors = get_post_meta($post->ID, "omb_color",true);
		$save_color = get_post_meta($post->ID, "omb_clr",true);
		$check_country = get_post_meta($post->ID, "omb_country",true);

		$checked = $is_checked ? "checked" : "";
		$colors = [ 'red' , 'green' ,'blue','yellow','black','white'];
		$label1 = __("Location", "our-metabox");
		$label2 = __("Country", "our-metabox");
		$label3 = __("Is Favorite", "our-metabox");
		$label4 = __("Color", "our-metabox");
		$label5 = __("Country", "our-metabox");

		$countries = [
			"Afghanistan","Bangladesh","Bhutan","India","Nepal","Sri Lanka","Maldives","Pakistan"];

		wp_nonce_field("omb_location", "omb_location_field");
		$metabox_html = <<<EOD
			<p>
				<label for="omb_location">{$label1}</label>
				<input type="text" id="omb_location" name="omb_location" value="{$location}" />
			</p>
			<p>
				<label for="omb_country">{$label2}</label>
			
				<input type="text" id="omb_country" name="omb_country" value="{$check_country}" />
			</p>
			<p>
				<label for="omb_is_favorite">{$label3}:</label>
				<input type="checkbox" id="omb_is_favorite" name="omb_is_favorite" value="1" {$checked}/>
			</p>
			<p>
				<label >{$label4}:</label>

		EOD;
//Multiple CheckBox
		foreach($colors as $color){
			$_color = ucwords($color);
			$checked = in_array($color,$save_colors)?"checked":'';
			$metabox_html .= <<<EOD
				<label for="omb_color_{$color}">{$_color}</label>
				<input type="checkbox" id="omb_color_{$color}" name="omb_color[]" value="{$color}" {$checked}/>
					
			EOD;
		}


		$metabox_html .= "</p>";
		//Radio Box
		$metabox_html .=<<<EOD
			<p>
				<label >{$label4}:</label>
		EOD;

		foreach($colors as $color) {
			$_color = ucwords($color);
			$checked = ($color == $save_color) ? "checked='checked'" : '';
			$metabox_html .= <<<EOD
        <label for="omb_clr_{$color}">{$_color}</label>
        <input type="radio" id="omb_clr_{$color}" name="omb_clr" value="{$color}" {$checked}/>
    EOD;
		}

		$metabox_html .= "</p>";
//DropDown Box
		$metabox_html .= <<<EOD
    <p>
        <label>{$label5}:</label>
        <select name="omb_country" id="omb_country">
EOD;

		foreach($countries as $country) {

			$_country = ucwords($country);
//
			$selected = ($country == $check_country) ? "selected='selected'" : '';
			$metabox_html .= <<<EOD
        <option id="omb_country_{$country}" value="{$country}" {$selected}>{$_country}</option>
    EOD;
		}

		$metabox_html .= <<<EOD
        </select>
    </p>
EOD;

		echo $metabox_html;

	}

}

new OurMetaBox();


























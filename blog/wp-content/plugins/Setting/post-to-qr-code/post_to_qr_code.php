<?php
/**
 * Plugin Name: Post to QR Code
 * Plugin URI:
 * Description:Get QR code from any post.
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: post-to-qr-code
 * Domain Path:/languages/
 * @package word-count
 */
//function wordcount_activation_hook(){}
//register_activation_hook("__FILE__", "wordcount_activation_hook");
//function wordcount_deactivation_hook(){}
//register_deactivation_hook("__FILE__", "wordcount_deactivation_hook");

function pqrc_load_textdomain(){
	load_plugin_textdomain('post-to-qr-code', false, dirname(__FILE__) .'/languages/');

}
add_action("plugins_loaded", "pqrc_load_textdomain");


function pqrc_display_qr_code($content){
	$current_post_id = get_the_ID();
	$current_post_type = get_post_type($current_post_id);
	$current_post_title = get_the_title($current_post_id);
	$current_post_url = urlencode(get_the_permalink($current_post_id));
	$excluded_post_type = apply_filters("pqrc_exclude_post_type", array());
	//Post type check
	if(in_array($current_post_type, $excluded_post_type)){
		return $content;
	}
    // Dimension check
	//  //Setting Option start

	$height = get_option("pqrc_height");
	$width = get_option("pqrc_width");
	$height = $height ?$height:100;
	$width = $width ?$width:100;
	$dimension = apply_filters("pqrc_post_dimension","{$width}x{$height}");
	//  //Setting Option end

	//image Attributes
	$img_attributes = apply_filters("pqrc_img_attributes", null);
	$image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?&qzone=1&margin=0&size=%s&ecc=L&data=%s',$dimension, $current_post_url);
	$content .= sprintf("<div class='qrcode'><img %s src='%s' alt='%s'/></div>",$img_attributes, $image_src, $current_post_title);
	return $content;
}
add_filter("the_content", "pqrc_display_qr_code");
//  //Setting Option
function pqrc_setting_init(){
	add_settings_field("pqrc_height",__(" QR Code Height", "post-to-qr-code"), "pqrc_display_qrcode_height", "general");
	add_settings_field("pqrc_width",__(" QR Code Width", "post-to-qr-code"), "pqrc_display_qrcode_width", "general");
	register_setting("general", "pqrc_height");
	register_setting("general", "pqrc_width");
}
function pqrc_display_qrcode_height(){
	$height = get_option("pqrc_height");
	printf("<input type='text' name='%s' id='%s' value='%s' />","pqrc_height","pqrc_height", $height);
}
function pqrc_display_qrcode_width(){
	$width = get_option("pqrc_height");
	printf("<input type='text' name='%s' id='%s' value='%s' />","pqrc_width","pqrc_width", $width);
}
add_action("admin_init","pqrc_setting_init");



















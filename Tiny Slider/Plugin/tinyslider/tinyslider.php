<?php
/**
 * Plugin Name: Tiny-Slider
 * Plugin URI:
 * Description:Get QR code from any post.
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: tiny-slider
 * Domain Path:/languages/
 * @package word-count
 */

function tinys_load_shortcode(){
	load_textdomain("tiny-slider",false,dirname(__FILE__).'/languages/');
};
add_action("plugins_loaded", "tinys_load_shortcode");
function tinys_init(){
	add_image_size("tiny-slider", 500, 400,true);
}
add_action("init", "tinys_init");
function tinys_assets(){
	wp_enqueue_style('tiny-slider-css',"//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css",null,"1.0.0");
	wp_enqueue_script("tiny-slider-js","//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js",null,"1.0.0",true);
	wp_enqueue_script("tiny-main-js",plugin_dir_url(__FILE__).'/assets/js/main.js',array('jquery'),"1.0",true);
}
add_action("wp_enqueue_scripts","tinys_assets");
function tinys_shortcode_tslider($arguments,$contents){
	$defaults = array(
		'width'=>800,
		'height'=>500,
		'id'=>'',
	);
	$attributes = shortcode_atts($defaults,$arguments);
	$content = do_shortcode($contents);
	$shortcode_Output = <<<EOD
		<div id ="{$attributes['id']}" style="width:{$attributes['width']};height:{$attributes['height']}">
			<div class="slider">
			{$content};
			</div>
		</div>
	EOD;
	return $shortcode_Output;

}
add_shortcode("tslider", "tinys_shortcode_tslider");
function tinys_shortcode_tslide($arguments){
	$defaults = array(
		'caption'=>'',
		'size'=> "tiny-slider",
		'id'=>'',
	);
	$attributes = shortcode_atts($defaults,$arguments);
	$img_src = wp_get_attachment_image_src($attributes["id"], $attributes['size']);
	$shortcode_output = <<<EOD
		<div class="slide">
			<p><img src="$img_src[0]" alt="{$attributes['caption']}"></p>
			<p>{$attributes['caption']}</p>
		</div>
	EOD;
	return $shortcode_output;

}
add_shortcode("tslide", "tinys_shortcode_tslide");











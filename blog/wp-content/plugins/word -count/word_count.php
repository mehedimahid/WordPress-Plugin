<?php
/**
 * Plugin Name: Word Count
 * Plugin URI:
 * Description:Count Word from any WordPress Post.
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: word-count
 *
 * @package word-count
 */
//function wordcount_activation_hook(){}
//register_activation_hook("__FILE__", "wordcount_activation_hook");
//function wordcount_deactivation_hook(){}
//register_deactivation_hook("__FILE__", "wordcount_deactivation_hook");

function wordcount_load_textdomain(){
	load_plugin_textdomain('word-count', false, dirname(__FILE__) .'/languages/');

}
add_action("plugins_loaded", "wordcount_load_textdomain");

function wordcount_count_words($content){
	$stripped_content = strip_tags($content);//content a html tag thakle ta delete korbe,
	$wordCount = str_word_count($stripped_content);
	$label = __('Total number of Word', 'word-count');
	$label = apply_filters('wordcount_heading', $label);
	$tag = apply_filters('wordcount_tag', "h2");
	$content.= sprintf('<%s>%s : %s</%s>',$tag, $label, $wordCount,$tag);
//	error_log( print_r( [ $tag, $label  ], true ) . "\n\n", 3, __DIR__ . '/log.txt' );
	return $content;
}
add_filter("the_content", "wordcount_count_words");



function wordcount_reading_time($content){
	$stripped_content = strip_tags($content);
	$wordCount = str_word_count($stripped_content);
	$reading_minutes = floor($wordCount / 200);//1m =200words
	$reading_seconds = floor($wordCount % 200/(200/60));;
	$is_vasiable = apply_filters('wordcount_display_readingtime', 1);
	if($is_vasiable){
		$label = __('Total Reading Time', 'word-count');
		$label = apply_filters('wordcount_reading_heading', $label);
		$tag = apply_filters('wordcount_reading_tag', "h3");
		$content.= sprintf('<%s>%s : %s minute %s seconds</%s>',$tag, $label,$reading_minutes, $reading_seconds,$tag);
	}
return $content;
}
add_filter("the_content", "wordcount_reading_time");





<?php
/**
 * Plugin Name: Assets Ninja
 * Plugin URI:
 * Description:.
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: assets-ninja
 * Domain Path:/languages/
 * @package word-count
 */
define("ASN_ASSETS_DIR",plugin_dir_url(__FILE__) . '/assets/');
define("ASN_ASSETS_DIR_PUBLIC",plugin_dir_url(__FILE__) . '/assets/public');
define("ASN_ASSETS_DIR_ADMIN",plugin_dir_url(__FILE__) . '/assets/admin');
define("ASN_VERSION",time());
class AssetsNinja {
	function __construct() {
		add_action('init', array($this, 'load_textdomain'));
		add_action('wp_enqueue_scripts', array($this, 'load_frontend_assets'));
		add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
	}

	function load_textdomain() {
		load_plugin_textdomain('assets-ninja', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	function load_frontend_assets() {
		//array এর মধ্যে ডিপেন্ডেন্সি দিলে বুজায় যে কে কার ওপর ডিপেন্ডেন্সি । যেমন main.js another.js এর ওপর ডিপেন্ডেন্সি tai age another.js run korbe and another.js
		//jehetu more.js ar opor dipendence so age more.js then another.js then main.js run korbe

//		wp_enqueue_script('asn-main-js', ASN_ASSETS_DIR_PUBLIC.'/js/main.js', array('jquery',"asn-another-js"), ASN_VERSION, true);
		wp_enqueue_script('asn-more-js', ASN_ASSETS_DIR_PUBLIC.'/js/more.js', array('jquery'), ASN_VERSION, true);
//		wp_enqueue_script('asn-another-js', ASN_ASSETS_DIR_PUBLIC.'/js/another.js', array('jquery','asn-more-js'), ASN_VERSION, true);
		$translable_language = array(
			"greeting" => __('Hello World', 'assets-ninja'),
		);
		$data = array(
			'name' => 'Mehedi',
			'birthday' => 24
		);
//php theke js a data patate chaile wp_localize_script(jar kache patabo tar handler, je name Js a use hbe, data name )
		wp_localize_script('asn-more-js', 'sitedata', $data);
		wp_localize_script('asn-more-js', 'language', $translable_language);

	}


	function load_admin_assets($screen) {
		$_screen = get_current_screen();
//		get_current_screen()ar sahajje screent ke amra obj akare pabo;
//		echo "<pre>";
//		print_r($_screen);
//		echo "</pre>";
//		die();
//		if("edit.php" ==$screen &&"page"== $_screen->post_type ){
//			wp_enqueue_script( 'asn-admin-js', ASN_ASSETS_DIR_ADMIN . '/js/admin.js', array( 'jquery' ), ASN_VERSION, true );
//		}
		if("edit-tags.php" ==$screen &&"category"== $_screen->taxonomy ){
			wp_enqueue_script( 'asn-admin-js', ASN_ASSETS_DIR_ADMIN . '/js/admin.js', array( 'jquery' ), ASN_VERSION, true );
		}
	}
//function load_admin_assets($screen) {
//		$_screen = get_current_screen();
//		if("options-general.php" ==$screen){
//			wp_enqueue_script( 'asn-admin-js', ASN_ASSETS_DIR_ADMIN . '/js/admin.js', array( 'jquery' ), ASN_VERSION, true );
//		}
//	}
}

new AssetsNinja();

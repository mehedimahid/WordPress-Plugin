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
define("ASN_ASSETS_DIR",plugin_dir_url(__FILE__) . 'assets/');
define("ASN_ASSETS_DIR_PUBLIC",plugin_dir_url(__FILE__) . 'assets/public');
define("ASN_ASSETS_DIR_ADMIN",plugin_dir_url(__FILE__) . 'assets/admin');
define("ASN_VERSION",time());
class AssetsNinja {
	function __construct() {
		add_action("init", array($this, "asn_init"));
		add_action('init', array($this, 'load_textdomain'));
		add_action('wp_enqueue_scripts', array($this, 'load_frontend_assets'));
		add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
		add_shortcode("bgmedia", array($this, 'asn_shortcode_media'));
	}
	function asn_init(){
		//purber kono file ke new file dara replace korar jonno wp_deregister use kora hoy
	//akhane //cdnjs.cloudflare.com theke //cdn.jsdelivr.net replace kora hoyeche.
//		wp_deregister_script("tiny-slider-js");
//		wp_register_script("tiny-slider-js","//cdn.jsdelivr.net/npm/tiny-slider@2.9.4/dist/min/tiny-slider.min.js", null, "1.0", true);
	}
	function load_textdomain() {
		load_plugin_textdomain('assets-ninja', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	function load_frontend_assets() {

//		wp_enqueue_script('asn-main-js', ASN_ASSETS_DIR_PUBLIC.'/js/main.js', array('jquery',"asn-another-js"), ASN_VERSION, true);
//		wp_enqueue_script('asn-more-js', ASN_ASSETS_DIR_PUBLIC.'/js/more.js', array('jquery'), ASN_VERSION, true);
//		wp_enqueue_script('asn-another-js', ASN_ASSETS_DIR_PUBLIC.'/js/another.js', array('jquery','asn-more-js'), ASN_VERSION, true);
	wp_enqueue_style("asn-main-css", ASN_ASSETS_DIR_PUBLIC . "/css/main.css",null,ASN_VERSION);
		$bg_media_src = wp_get_attachment_image_src(108, 'medium');
		$data =<<<EOD
			#bgmedia{
				background-image: url($bg_media_src[0]);
			}
		EOD;
		wp_add_inline_style("asn-main-css", $data );

		$js_file = array(
			'asn-main-js' => array("path"=>ASN_ASSETS_DIR_PUBLIC.'/js/main.js','dep' =>array('jquery',"asn-another-js")),
			'asn-another-js' => array("path"=>ASN_ASSETS_DIR_PUBLIC.'/js/another.js','dep' =>array('jquery',"asn-more-js")),
			'asn-more-js' => array("path"=>ASN_ASSETS_DIR_PUBLIC.'/js/more.js','dep' =>array('jquery'))
		);
		foreach ($js_file as $handle => $js_fileinfo) {
			wp_enqueue_script($handle, $js_fileinfo['path'], $js_fileinfo['dep'], ASN_VERSION, true);
		}
		$translable_language = array(
			"greeting" => __('Hello World', 'assets-ninja'),
		);
		$data = array(
			'name' => 'Mehedi',
			'birthday' => 24
		);
		wp_localize_script('asn-more-js', 'sitedata', $data);
		wp_localize_script('asn-more-js', 'language', $translable_language);
		$dt =<<<EOD
			alert("Hello from inline script");
		EOD;
		wp_add_inline_script('asn-more-js', $dt);

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
	function asn_shortcode_media($attrs) {
		$shortcode_output = <<<EOD
		<div id="bgmedia"></div>
		EOD;
		return $shortcode_output;
	}
}

new AssetsNinja();

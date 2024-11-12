<?php
/**
 * Plugin Name: PopUp Creator
 * Plugin URI: 
 * Description: 
 * Requires at least: 
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI: 
 * License:  GPLv2 or later
 * License URI: 
 * Text Domain: popupcreator
 * Domain Path: /languages/
 * @package 
 */
class PopUpCreator {
	function __construct() {
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
		add_action('init', array($this, 'register_popup_creator'));
		add_action('init', array($this, 'register_popup_images_size'));
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'wp_footer', array( $this, 'print_modal_markup' ) );
	}
	function load_plugin_textdomain() {
		load_plugin_textdomain('popupcreator', FALSE, basename(dirname(__FILE__)) . '/languages');
	}
	function register_popup_images_size() {
		add_image_size('popupcreator-landscape', 400, 300, true);
		add_image_size('popupcreator-square', 300, 300, true);
	}
	function load_assets(){
		wp_enqueue_style('popupcreator', plugin_dir_url(__FILE__) . 'css/popupcreator.css');
		wp_enqueue_script( 'plainmodal-js', plugin_dir_url( __FILE__ ) . "assets/js/plain-modal.min.js", null, "1.0.27", true );
		wp_enqueue_script( 'popupcreator-main', plugin_dir_url( __FILE__ ) . "assets/js/popupcreator-main.js", array(
			'jquery',
			'plainmodal-js'
		), time(), true );

	}
	function register_popup_creator() {
		$labels = array(
			'name' => __('Popups', 'popupcreator'),
			'singular_name' => __('Popup', 'popupcreator'),
			'featured_image' => __('Popup Image', 'popupcreator'),
			'set_featured_image' => __('Set Popup Image', 'popupcreator'),
		);
		$args = array(
			'label'=>__('Popups', 'popupcreator'),
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'has_archive' => false,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'delete_with_user'=>false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'query_var' => true,
			'map_meta_cap' => true,
			'rewrite' => array( 'slug' => 'popup', 'with_front' => false ),
			'supports'=>array('title','thumbnail')
		);
		register_post_type('popup', $args);
	}
	function print_modal_markup(){
	?>
		<div id="modal-content">
			<div>
				<img id="close-button" width="30"
					src="<?php echo plugin_dir_url(__FILE__)."assets/img/x.png" ?>"
				>
			</div>
            <img src="https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="Popup Image">

        </div>
	<?php
	}
}
new PopUpCreator;


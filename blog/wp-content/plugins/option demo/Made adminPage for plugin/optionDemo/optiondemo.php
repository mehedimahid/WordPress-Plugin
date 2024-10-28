<?php
/**
 * Plugin Name: Option Demo
 * Plugin URI:
 * Description:
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author:Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: optionsdemo
 * Domain Path:/languages/
 * @package our-metabox
 */
require_once (plugin_dir_path(__FILE__) . '/option_demo_form.php');
// Settings Page: Option Demo
class optionsdemo_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'optionsdemo_create_settings' ) );
		add_action( 'admin_init', array( $this, 'optionsdemo_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'optionsdemo_setup_fields' ) );
		add_action( 'plugins_loaded', array( $this, 'optionsdemo_bootstrap' ) );
        add_filter('plugin_action_links_'.plugin_basename( __FILE__ ), array($this,'optionsdemo_add_settings_link') );

	}
    //Plugin ar active/deactive option  ar pase setting option show kora
    function optionsdemo_add_settings_link($links){
        $newLinks = sprintf("<a href='%s'>%s</a>",'options-general.php?page=optionsdemo', __('Setting','optionsdemo'));
        $links[] = $newLinks;
        return $links;
    }
	function optionsdemo_bootstrap(){
			load_plugin_textdomain('optionsdemo', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
	public function optionsdemo_create_settings() {
		$page_title = __('Option Demo', 'optionsdemo');
		$menu_title = __('Option Demo', 'optionsdemo');
		$capability = 'manage_options';
		$slug = 'optionsdemo';
		$callback = array($this, 'optionsdemo_settings_content');
		add_options_page($page_title, $menu_title, $capability, $slug, $callback);
	}

	public function optionsdemo_settings_content() { ?>
		<div class="wrap">
			<h1><?php _e('Option Demo','optionsdemo')?></h1>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'optionsdemo' );
					do_settings_sections( 'optionsdemo' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function optionsdemo_setup_sections() {
		add_settings_section( 'optionsdemo_section', 'Demonstration of plugin setting page', array(), 'optionsdemo' );
	}

	public function optionsdemo_setup_fields() {
		$fields = array(
			array(
				'label' => __('Latitude', 'optionsdemo'),
				'id' => 'optionsdemo_latitude',
				'type' => 'text',
				'section' => 'optionsdemo_section',
                'placeholder' => __('Latitude', 'optionsdemo'),
			),
			array(
				'label' => __('Longitude', 'optionsdemo'),
				'id' => 'optionsdemo_longitude',
				'type' => 'text',
				'section' => 'optionsdemo_section',
				'placeholder' => __('Langitude', 'optionsdemo'),

			),
			array(
				'label' => __('API Key', 'optionsdemo'),
				'id' => 'optionsdemo_apikey',
				'type' => 'text',
				'section' => 'optionsdemo_section',
			),
			array(
				'label' => __('Zoom Label', 'optionsdemo'),
				'id' => 'optionsdemo_zoomlabel',
				'type' => 'text',
				'section' => 'optionsdemo_section',
                'placeholder' => __('100%', 'optionsdemo'),
			),
			array(
				'label' => __('External CSS', 'optionsdemo'),
				'id' => 'optionsdemo_externalcss',
				'type' => 'textarea',
				'section' => 'optionsdemo_section',
			),
			array(
				'label' => __('Expiry Date', 'optionsdemo'),
				'id' => 'optionsdemo_expirydate',
				'type' => 'date',
				'section' => 'optionsdemo_section',
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'optionsdemo_field_callback' ), 'optionsdemo', $field['section'], $field );
			register_setting( 'optionsdemo', $field['id'] );
		}
	}

	public function optionsdemo_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
				case 'textarea':
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
					$field['id'],
					$placeholder,
					$value
					);
					break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
}
new optionsdemo_Settings_Page();
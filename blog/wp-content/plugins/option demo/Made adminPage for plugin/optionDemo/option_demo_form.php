<?php
class OptionDemoForms{
	function __construct(){
		add_action( 'admin_menu', array( $this, 'optionsdemo_create_admin_page' ) );
		add_action('admin_post_optionsdemo_admin_page', array( $this, 'optionsdemo_save_form' ) );
	}
	function optionsdemo_create_admin_page(){
		$page_title = __('Op Admin Page', 'optionsdemo');
		$menu_title = __('Op Admin Page', 'optionsdemo');
		$capability = 'manage_options';
		$slug = 'optionsdemopage';
		$callback = array($this, 'optionsdemo_page_content');
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback);
	}
	function optionsdemo_page_content(){
	require_once plugin_dir_path(__file__).'/form.php';
	}
	function optionsdemo_save_form(){
		check_admin_referer('optionsdemo');
		if(isset($_POST["optionsdemo_form_longitude"])){
			update_option('optionsdemo_form_longitude', sanitize_text_field($_POST["optionsdemo_form_longitude"]));
		}
		wp_redirect('admin.php?page=optionsdemopage');
	}
}
new OptionDemoForms();
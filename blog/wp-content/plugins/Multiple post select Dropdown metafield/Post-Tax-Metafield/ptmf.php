
<?php
/**
* Plugin Name: Post Tax MetaField
* Plugin URI:
* Description:Metabox API
* Requires at least: 6.4
* Requires PHP: 7.2
* Version: 3.2.0
* Author:Mehedi Hasan
* Author URI:
* License: GPLv2 or later
* License URI:
* Text Domain: Post-Tax-Metafield
* Domain Path:/languages/
* @package our-metabox
*/
function ptmf_load_textdomain(){
	load_plugin_textdomain("Post-Tax-Metafield", FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action("plugins_loaded","ptmf_load_textdomain");

function ptmf_admin_assets(){
	wp_enqueue_style('ptmf-admin-style', plugin_dir_url( __FILE__ ) . 'assets/admin/css/style.css', null,time());
//	wp_enqueue_script('ptmf-admin-script', plugin_dir_url( __FILE__ ) . 'assets/admin/js/main.js', array('jquery','jquery-ui-datepicker'),time(),true);
}
add_action("admin_enqueue_scripts","ptmf_admin_assets");


function ptmf_add_metabox(){
	add_meta_box(
		"ptmf_select_post_mb",
		__("Select post", "Post-Tax-Metafield"),
		 "ptmf_display_metabox",
		array("page")//where show this meta box
	);
}
add_action("admin_menu",  "ptmf_add_metabox");

function ptmf_save_post($post_id){
	if(!ptmf_is_secured('ptmf_posts_nonce', "ptmf_posts", $post_id)){
		return $post_id;
	}
	$selected_post_id = $_POST["ptmf_posts"];
	if($selected_post_id>0){
		update_post_meta($post_id, "ptmf_selected_posts", $selected_post_id);
	}
	return $post_id;
}
add_action("save_post",  "ptmf_save_post");


function ptmf_display_metabox($post){
	$selected_post_id = get_post_meta($post->ID, "ptmf_selected_posts", true);
//	print_r( $selected_post_id);
	$args = array(
		"post_type" => "post",
		"posts_per_page"=>-1,
		"orderby" => "title",
		"order" => "ASC"
	);
	$dropdown_list='';
	$_posts= new WP_Query($args);
	while($_posts->have_posts()){

		$_posts->the_post();
		$selected = (in_array(get_the_id(),$selected_post_id))? 'selected' : '';
		$dropdown_list .= sprintf("<option %s value= '%s'>%s</option>",
			$selected,
			get_the_ID(),
			get_the_title()
		);
	}
	wp_reset_postdata();
	$label = __("Select Post", "Post-Tax-Metafield");
	wp_nonce_field('ptmf_posts','ptmf_posts_nonce');
	$metabox_html = <<<HTML
				<div class="field">
					<div class="field_c">
						<div class="label_c" >
							<label >{$label}:</label>
						</div>
						<div class="input_c"> 
						<select multiple="multiple" name="ptmf_posts[]" id="ptmf_posts">
							<option value="0">{$label}</option>
							{$dropdown_list}
						</select>	
						</div>
						<div class="float_c"></div>
					</div>
				</div>		
			HTML;
	echo $metabox_html;

}

if(!function_exists('ptmf_is_secured')){
	function ptmf_is_secured($nonce_field, $action, $post_id){
		$nonce = isset($_REQUEST[$nonce_field]) ?($_REQUEST[$nonce_field]) : '';
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
}


















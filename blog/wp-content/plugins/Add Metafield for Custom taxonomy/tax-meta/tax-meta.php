<?php
/**
 * Plugin Name: Taxonomy MetaBox
 * Plugin URI:
 * Description: Add taxonomy
 * Requires at least: 6.4
 * Requires PHP: 7.2
 * Version: 3.2.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 * License URI:
 * Text Domain: tex-meta
 * Domain Path: /languages/
 * @package tax-meta
 */

function taxm_load_textdomain() {
	load_plugin_textdomain('tax-meta', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'taxm_load_textdomain');

function taxm_bootstram() {
	register_meta("term", 'texm_extra_info', array(
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'single' => true,
		'description' => 'simple meta field for category tex',
		'show_in_rest' => true
	));
}
add_action('init', 'taxm_bootstram');
//add taxonomy and show
function taxm_add_category_form_fields($term_id) {
	$extra_info = get_term_meta($term_id, "texm_extra_info", true);
	?>
    <div class="form-field form-required term-name-wrap">
        <label for="extra-info"><?php _e("Extra info", "tax-meta"); ?></label>
        <input name="extra-info" id="extra-info" type="text" value="<?php echo esc_attr($extra_info); ?>" size="40" aria-required="true" aria-describedby="name-description">
        <p id="name-description"><?php _e("*  write something about your taxonomy", "tax-meta"); ?></p>
    </div>
	<?php
}
add_action("category_add_form_fields", "taxm_add_category_form_fields");
add_action('post_tag_add_form_fields', 'taxm_add_category_form_fields');
add_action('genre_add_form_fields', 'taxm_add_category_form_fields');
//edit taxonomy
function taxm_edit_category_form_fields($term) {
	$extra_info = get_term_meta($term->term_id, "texm_extra_info", true);
	?>
    <tr class="form-field form-required term-name-wrap">
        <th scope="row">
            <label for="extra-info">
				<?php _e("Extra info", "tax-meta"); ?>
            </label>
        </th>
        <td>
            <input name="extra-info" id="extra-info" type="text" value="<?php echo esc_attr($extra_info); ?>" size="40" aria-required="true" aria-describedby="name-description">
            <p id="name-description"><?php _e("* write something about your taxonomy", "tax-meta"); ?></p>
        </td>
    </tr>
	<?php
}
add_action("category_edit_form_fields", "taxm_edit_category_form_fields");
add_action('post_tag_edit_form_fields', 'taxm_edit_category_form_fields');
add_action('genre_edit_form_fields', 'taxm_edit_category_form_fields');
//save taxonomy
function taxm_save_category_meta($term_id) {
	if (isset($_POST['_wpnonce_add-tag']) && wp_verify_nonce($_POST['_wpnonce_add-tag'], 'add-tag')) {
		$extra_info = sanitize_text_field($_POST['extra-info']);
		update_term_meta($term_id, "texm_extra_info", $extra_info);
	}
}
add_action('create_category', "taxm_save_category_meta");
add_action('create_post_tag', 'taxm_save_category_meta');
add_action('create_genre', 'taxm_save_category_meta');
//update taxonomy
function taxm_update_category_meta($term_id) {
	if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], "update-tag_{$term_id}")) {
		$extra_info = sanitize_text_field($_POST['extra-info']);
		update_term_meta($term_id, "texm_extra_info", $extra_info);
	}
}
add_action('edit_category', 'taxm_update_category_meta');
add_action('edit_post_tag', 'taxm_update_category_meta');
add_action('edit_genre', 'taxm_update_category_meta');
////1st action hook for category
/// 2nd action hook for post tag
/// 3rd action hook for custom taxonomy
///
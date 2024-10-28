<h1>Option Demo Admin Page</h1>
<form action="<?php echo admin_url('admin-post.php') ?>" method="post">
	<?php
	wp_nonce_field('optionsdemo');
    $optionsdemo_longitude_data = get_option('optionsdemo_form_longitude');
	?>
    <label for="optionsdemo_form_longitude"><?php _e('Longitude:', 'optionsdemo') ?></label>
    <input type="text" id="optionsdemo_form_longitude" name="optionsdemo_form_longitude" value="<?php echo esc_attr($optionsdemo_longitude_data); ?>" />
    <input type="hidden" name="action" value="optionsdemo_admin_page">
	<?php
	submit_button('Save');
	?>

</form>
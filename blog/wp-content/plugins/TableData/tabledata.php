<?php
/**
 * Plugin Name: Data Table
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 *Text Domain: datatable
 *Domain Path: /languages/
 */

include_once 'class.persons-tables.php';
function datatable_load_textdomain(){
	load_plugin_textdomain('datatable',false,dirname(__FILE__).'/languages/');
}
add_action('plugin_loaded','datatable_load_textdomain');

function datatable_add_menu(){
	add_menu_page(
		__('Data Table', 'datatable'),
		__('Data Table', 'datatable'),
		'manage_options',
		'datatable',
		'datatable_show_dataset'
	);
}
add_action('admin_menu','datatable_add_menu');

function datatable_show_dataset(){
	include_once 'dataset.php';
	$table = new persons_tables();
	$table->set_data($data);
	$table->prepare_items();
	?>
	<div class="wrap">
		<h2><?php _e("Persons Information:",'datatable');?></h2>
		<form method="get">
			<?php
			$table->search_box('search','search_id');
			$table->display();
			?>

		</form>
	</div>
<?php
}



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
function datatable_search_by_name($item){
    $name = strtolower($item['name']);
    $search_name = sanitize_text_field($_REQUEST['s']);
    if(strpos($name,$search_name) !== false){
        return true;
    }
        return false;

}
function datatable_show_dataset(){
	include_once 'dataset.php';
    if(isset($_REQUEST['s'])){
        $data= array_filter($data, 'datatable_search_by_name');
    }
	$table = new persons_tables();
    $orderby = $_REQUEST['orderby']??'';
    $order = $_REQUEST['order']??'';
    if('age'==$orderby){
        if ('asc'==$order){
            usort($data, function($a, $b) {
                return $a['age'] <=> $b['age'];
            });
        }else{
	        usort($data, function($a, $b) {
		        return $b['age'] <=> $a['age'];
	        });
        }
    }elseif ('name'==$orderby){
	    if ('asc'==$order){
		    usort($data, function($a, $b) {
			    return $a['name'] <=> $b['name'];
		    });
	    }else{
		    usort($data, function($a, $b) {
			    return $b['name'] <=> $a['name'];
		    });
	    }
    }
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
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
		</form>
	</div>
<?php
}



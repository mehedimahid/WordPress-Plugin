<?php
if (!class_exists("WP_List_Table")) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class Persons_Tables extends WP_List_Table{
	function __construct($args = array()) {
		parent::__construct($args);
	}
	function set_data($data){
		$this->items=$data;
	}
	function get_columns(){
		return  array(
			'cb' => '<input type="checkbox" />',
			'name' => __('Name', 'datatable'),
			'email' => __('E-mail', 'datatable'),
			'age' => __('Age', 'datatable'),
			'sex' => __('Sex', 'datatable'),
		);
	}
	function prepare_items() {
		$this->_column_headers = array($this->get_columns());
	}
	function column_default( $item, $column_name ) {
	return $item[$column_name];
	}

}
<?php
if (!class_exists("WP_List_Table")) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class DBDUsers extends WP_List_Table {
	public function __construct( $data ) {
		parent::__construct(  );
		$this->items= $data;
	}

	public function get_columns() {
		return [
			'cb' => '<input type="checkbox" />',
			'name' => __('Name', 'dbd'),
			'email' => __('E-mail', 'dbd'),
			'action' => __('Action', 'dbd')
		];
	}
	function column_cb( $item ) {
		return "<input type='checkbox' value='{$item['id']}' />";
	}
	function column_action( $item ) {
		//URL verified  1st step set action and name like dbd_edit and nn(nn url a show korbe)
		$link = wp_nonce_url(admin_url("?page=dbd-demo&pid=").$item['id'],'dbd_edit','nn');
		return "<a href='{$link}'>Edit</a>";
	}
	public function column_default( $item, $column_name ) {
		return $item[$column_name];
	}
	public function prepare_items() {
		$this->_column_headers =array( $this->get_columns(),[],[]);
		$paged = $_REQUEST[ 'paged' ]??1;
		$per_page = 3;
		$total_item = count( $this->items );
		$data_chunk = array_chunk( $this->items, $per_page );
		$this->items = $data_chunk[$paged-1];
		$this->set_pagination_args( array(
			'total_items' => $total_item,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_item/$per_page ),
		));
	}
}



<?php
if (!class_exists("WP_List_Table")) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class Persons_Tables extends WP_List_Table{

	private $_items;
	function set_data($data){
		$this->_items=$data;
	}
	function get_columns(){
		return  array(
			'cb' => '<input type="checkbox" />',
			'name' => __('Name', 'datatable'),
			'email' => __('E-mail', 'datatable'),
			'age' => __('Age', 'datatable'),
			'sex' => __('Gender', 'datatable'),
		);
	}
	function get_sortable_columns(){
		return  array(
			'name' => array('name', false),
			'age' => array('age', false),
		);
	}
	function column_cb( $item ) {
		return "<input type='checkbox' value='{$item['id']}' />";
	}
	function column_name( $item ) {
		return "<strong>{$item['name']}</strong>";
	}
	function column_email( $item ) {
		return "<em>{$item['email']}</em>";
	}
	function extra_tablenav( $which ) {
		if ($which == 'top') {
			?>
			<div class="actions alignleft">
				<select name="filter_gen" id="filter_gen">
					<option value="all">All</option>
					<option value="M">Male</option>
					<option value="F">Female</option>
				</select>
				<?php
				submit_button(__('Filter', 'datatable'), 'primary', 'submit', false);
				?>
			</div>
		<?php
		}
	}
	function prepare_items() {
		$this->_column_headers = array($this->get_columns(),array(),$this->get_sortable_columns());
		$paged= $_REQUEST['paged']??1;
		$per_page=3;
		$total_items=count($this->_items);
		$data_chunk= array_chunk($this->_items, $per_page);
		$this->items = $data_chunk[$paged-1];//কারণ অ্যারের ইনডেক্স ০ থেকে শুরু
		$this->set_pagination_args( array(
			'total_items'=>$total_items,
			'per_page'=>$per_page,
			'total_pages'=>ceil($total_items/$per_page)

		));
	}
	function column_default( $item, $column_name ) {
	return $item[$column_name];
	}

}
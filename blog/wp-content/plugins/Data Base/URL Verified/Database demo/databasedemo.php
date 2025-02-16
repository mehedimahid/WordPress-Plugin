<?php
/**
 * Plugin Name: DataBase Demo
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 *Text Domain: dbd
 *Domain Path: /languages/
 */

define('DBD_VERSION','1.2');

require_once ("class.dbdusers.php");

function dbd_demo_activate(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	$sql = "CREATE TABLE $table_name (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) ,
    email VARCHAR(250) ,
    PRIMARY KEY (id)
);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	add_option('dbd_db_version',DBD_VERSION);
	if(get_option('dbd_db_version') != DBD_VERSION){
		$sql = "CREATE TABLE $table_name (
		    id INT NOT NULL AUTO_INCREMENT,
		    name VARCHAR(250) ,
		    email VARCHAR(250) ,
		    age INT,
		    PRIMARY KEY (id)
		);";

		dbDelta($sql);//dbDelta new column add korte pare but delete kore pare na
		update_option('dbd_db_version',DBD_VERSION);
	}
}
register_activation_hook( __FILE__, 'dbd_demo_activate' );

function dbd_demo_drop_column(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	if(get_option('dbd_db_version') != DBD_VERSION){
		$query = "ALTER TABLE $table_name DROP COLUMN age";//age column delete
		$wpdb->query($query);
	}
	update_option('dbd_db_version',DBD_VERSION);
}
add_action('plugins_loaded', 'dbd_demo_drop_column');
add_action('admin_enqueue_scripts', function($hook){
    if('toplevel_page_dbd-demo'==$hook){
        echo "Hello";
        wp_enqueue_style('dbddemo-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    }
});
function dbd_demo_insert_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	$wpdb->insert($table_name,[
		'name'=>'John Doe',
		'email'=>'john@doe.com',
	]);
	$wpdb->insert($table_name,[
		'name'=>'Jane Doe',
		'email'=>'jane@doe.com',
	]);
}
register_activation_hook( __FILE__, 'dbd_demo_insert_data' );
function dbd_demo_flush_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . "persons";
	$query="TRUNCATE TABLE $table_name";
	$wpdb->query($query);

}
register_deactivation_hook( __FILE__, 'dbd_demo_flush_data' );

add_action('admin_menu', function(){
	add_menu_page(
		__('DB Demo','dbd'),
		__('DB Demo','dbd'),
		'manage_options',
		'dbd-demo',
		'dbd_demo_admin_page'
	);
});
function dbd_demo_admin_page() {
	global $wpdb;
	//URL verified  2nd step verify nonce
    if(isset($_GET['pid'])){
        if(!isset($_GET['nn'])|| !wp_verify_nonce($_GET['nn'], 'dbd_edit')){
            wp_die(__('Sorry you are not allowed to do this', 'dbd'));
        }
    };
	// পেজের শিরোনাম দেখানো
	echo '<h2>DB Demo</h2>';

	// URL থেকে 'pid' নিরাপদে গ্রহণ করা
	$id = sanitize_key($_GET['pid'] ?? 0);
	$result = null;

	if ($id) {
		// ডাটাবেজ থেকে ডাটা আনা
		$result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}persons WHERE id = %d", $id));
//		if ($result) {
//			echo "Name: " . esc_html($result->name) . "<br>";
//			echo "E-mail: " . esc_html($result->email) . "<br>";
//		}
	}


	?>

    <div class="form_box">
        <div class="form_box_header">
            <?php _e('Data Form','dbd')?>
        </div>
        <div class="form_box_content">
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
		        <?php
                    wp_nonce_field('dbd_demo', 'dbd_nonce');
                ?>
                <input type="hidden" name="action" value="dbd_add_record">
                <label>
                    <strong>Name</strong>
                </label><br/>
                <input type="text" class="form_text" name="name" value="<?php echo $result ? esc_attr($result->name) : ''; ?>" required><br/>

                <label>
                    <strong>Email</strong>
                </label><br/>
                 <input type="email" class="form_text" name="email" value="<?php echo $result ? esc_attr($result->email) : ''; ?>" required><br/>
                <div style="display: flex; gap: 10px;">
                    <?php if ($id): ?>
                        <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
                        <?php submit_button('Update record', 'primary', 'dbd_submit',false); ?>
                        <a href="<?php echo admin_url('?page=dbd-demo'); ?>" class="button button-primary">Add New Record</a>
                    <?php else: ?>
                        <?php submit_button('Add record', 'primary', 'dbd_submit'); ?>
                    <?php endif; ?>
                </div>
            </form>

        </div>
    </div>
    <div class="form_box" style="margin-top: 30px">
        <div class="form_box_header">
            <?php _e('Users','dbd')?>
        </div>
        <div class="form_box_content">
	        <?php
	        global $wpdb;

	        $dbdtusers = $wpdb->get_results("SELECT id, name, email FROM {$wpdb->prefix}persons", ARRAY_A);

		        $dbdusers = new DBDUsers($dbdtusers);
		        $dbdusers->prepare_items();
		        $dbdusers->display();

	        ?>

        </div>
    </div>
	<?php
}

// ফর্ম সাবমিশন হ্যান্ডলিং
add_action('admin_post_dbd_add_record', function () {
	global $wpdb;

	// নন্স যাচাই
	if (!isset($_POST['dbd_nonce']) || !wp_verify_nonce($_POST['dbd_nonce'], 'dbd_demo')) {
		wp_die('you are not do this');
	}

	if (isset($_POST['name'], $_POST['email'])) {
		$name = sanitize_text_field($_POST['name']);
		$email = sanitize_email($_POST['email']);
		$id = isset($_POST['id']) ? absint($_POST['id']) : 0;

		if ($id) {
			// রেকর্ড আপডেট করা
			$wpdb->update(
				"{$wpdb->prefix}persons",
				['name' => $name, 'email' => $email],
				['id' => $id],
				['%s', '%s'],
				['%d']
			);
			// URL verified 3rd step wp_redirect a sorasori nonce bosano jay tai age create kore nite hoy
            $nonce = wp_create_nonce('dbd_edit');
			wp_redirect(admin_url('admin.php?page=dbd-demo&pid=' . $id . "&nn={$nonce}"));
		} else {
			// নতুন রেকর্ড ইনসার্ট করা
			$wpdb->insert(
				"{$wpdb->prefix}persons",
				['name' => $name, 'email' => $email],
				['%s', '%s']
			);
            $new_id = $wpdb->insert_id;
			$nonce = wp_create_nonce('dbd_edit');
			wp_redirect(admin_url('admin.php?page=dbd-demo&pid=' . $new_id."&nn={$nonce}"));

//			wp_redirect(admin_url('admin.php?page=dbd-demo'));
		}

		exit;
	} else {
		wp_die('Invalid form submission.');
	}
});


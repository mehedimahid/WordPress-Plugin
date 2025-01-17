<?php

/**
 * Plugin Name: WPDB demo
 * Plugin URI: 
 * Description: Demonstration of WPDB Methods
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI: https://github.com/mehedimahid
 * License: GPLv2 or later
 *Text Domain: wpdb-demo
 *Domain Path: /languages/
 */
function wpdb_demo_init(){
    global $wpdb;
    $table_name = $wpdb->prefix . "persons";
    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        age int,
        primary key(id)
    )";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__,'wpdb_demo_init');

add_action('admin_enqueue_scripts','wpdb_demo_enqueue_scripts');
function wpdb_demo_enqueue_scripts($hook){
    if ("toplevel_page_wpdb-demo" === $hook) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'wpdb-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'wpdb-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'display_result' );
        wp_localize_script(
            'wpdb-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }
}
add_action('wp_ajax_display_result', 'wpdb_demo_display_result');
function wpdb_demo_display_result(){
    global $wpdb;
    $table_name = $wpdb->prefix . "persons";
    if(wp_verify_nonce($_POST['nonce'], 'display_result')){
        $task = $_POST['task'];
        if('add-new-record' == $task){
//            $person = [
//                    'name'=>'Jone Deo',
//                    'email'=>'jone@doe.com',
//                    'age'=>25,
//            ];
            $person2 = [
                'name'=>'Jene Deo',
                'email'=>'jene@doe.com',
                'age'=>25,
            ];
            $wpdb->insert($table_name,$person2,['%s','%s','%d']);
            echo "New record added successfully<br/>";
            echo "ID: {$wpdb->insert_id}\n}";
        }elseif ('replace-or-insert'==$task){
            //ID পেলে রিপ্লেস হবে। না পেলে নতুন রেকর্ড অ্যাড হবে
//            $person2 = [
//                    'id'=>3,
//                'name'=>'Jimmy Deo',
//                'email'=>'jimmy@doe.com',
//                'age'=>28,
//            ];
            $person2 = [
                    'id'=>4,
                'name'=>'Jane Deo',
                'email'=>'Jane@doe.com',
                'age'=>28,
            ];
            $wpdb->replace($table_name,$person2);
            echo "Operation Done successfully<br/>";
            echo "ID: {$wpdb->insert_id}\n}";
        }elseif ('update-data' ==$task){
            $person=['age'=>30];
            $result= $wpdb->update($table_name,$person,['id'=>2]);//ID পেলে তবেই ডাটা আপডেট হবে
            echo "Operation Done successfully . Result = {$result}<br/>";
        }elseif ('load-single-row' ==$task){
          $data=  $wpdb->get_row("SELECT * FROM $table_name WHERE id=2 ",ARRAY_A);
          print_r($data);
        }elseif ('load-multiple-row' ==$task){
//            $data=  $wpdb->get_results("SELECT * FROM $table_name ");
//            $data=  $wpdb->get_results("SELECT * FROM $table_name WHERE id>2 ",ARRAY_A);
            $data=  $wpdb->get_results("SELECT email, id, name, age FROM $table_name ",OBJECT_K);//প্রথম টাকে প্রিমারি কি ধরবে

            print_r($data);
        }elseif ('add-multiple' ==$task){
            $persons = [
                    [
                        'name'=>'Jone Deo',
                        'email'=>'jone@doe.com',
                        'age'=>28,
                    ],
                    [
                        'name'=>'David Deo',
                        'email'=>'david@doe.com',
                        'age'=>29,
                    ],
            ];
            foreach($persons as $person){
                 $wpdb->insert($table_name,$person);
            }
            $data=  $wpdb->get_results("SELECT  id, name, email, age FROM $table_name ",OBJECT_K);

            print_r($data);
        }elseif ('prepared-statement' ==$task){
            $id=1;
            $age = 27;
            $email = 'jone@doe.com';
//            $prepared_data=  $wpdb->prepare("SELECT * FROM $table_name WHERE id>%d ",$id);
//            $prepared_data=  $wpdb->prepare("SELECT * FROM $table_name WHERE email=%s ",$email);
            $prepared_data=  $wpdb->prepare("SELECT * FROM $table_name WHERE id>%d and age>%d ",$id, $age);
            $data = $wpdb -> get_results($prepared_data,ARRAY_A);
            print_r($data);
        }elseif ('single-column' ==$task){
            $query = "SELECT email FROM $table_name";
            $data = $wpdb->get_col($query);//single column nibe
            print_r($data);
        }elseif ('single-var' ==$task){
//           $result = $wpdb->get_var("SELECT count(*) FROM $table_name");
//           echo "Total users:{$result}\n";
//
//           $result = $wpdb->get_var("SELECT name, email FROM $table_name",0,0);
//            echo "Name of 1st user:{$result}\n";
//            $result = $wpdb->get_var("SELECT name, email FROM $table_name",1,0);
//            echo "1st user's email is:{$result}\n";
//
//            $result = $wpdb->get_var("SELECT name, email FROM $table_name",0,3);
//            echo "Name of 2nd user:{$result}\n";
            $result = $wpdb->get_var("SELECT name, email FROM $table_name",1,3);
            echo "2nd user's email is:{$result}\n";

        }elseif ('delete-data' ==$task){
//            $result= $wpdb->delete($table_name, ['id'=>2]);
            $result= $wpdb->delete($table_name, ['name'=>'David Deo']);
            echo $result;
        }
    }
    die(0);
}

add_action('admin_menu', function(){
    add_menu_page(
        __('WPDB Demo', 'wpdb_demo'),
        __('WPDB Demo', 'wpdb_demo'),
        'manage_options',
        'wpdb-demo',
        'wpdbdemo_admin_page',
    );
});
function wpdbdemo_admin_page() {
    ?>
    <div class="container" style="padding-top:20px;">
        <h1>WPDB Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='add-new-record'>Add New Data</button>
                    <button class="action-button" data-task='replace-or-insert'>Replace or Insert</button>
                    <button class="action-button" data-task='update-data'>Update Data</button>
                    <button class="action-button" data-task='load-single-row'>Load Single Row</button>
                    <button class="action-button" data-task='load-multiple-row'>Load Multiple Row</button>
                    <button class="action-button" data-task='add-multiple'>Add Multiple Row</button>
                    <button class="action-button" data-task='prepared-statement'>Prepared Statement</button>
                    <button class="action-button" data-task='single-column'>Display Single Column</button>
                    <button class="action-button" data-task='single-var'>Display Variable</button>
                    <button class="action-button" data-task='delete-data'>Delete Data</button>
                </div>
            </div>
            <div class="pure-u-3-4">
                <div class="plugin-demo-content">
                    <h3 class="plugin-result-title">Result</h3>
                    <div id="plugin-demo-result" class="plugin-result"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
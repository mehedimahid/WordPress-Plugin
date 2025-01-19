<?php
/**
 * Plugin Name: Option demo
 * Plugin URI:
 * Description: Demonstration of WPDB Methods
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI: https://github.com/mehedimahid
 * License: GPLv2 or later
 *Text Domain: options-demo
 *Domain Path: /languages/
 */

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_options-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'options-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'options-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'options_display_result' );
        wp_localize_script(
            'options-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }
} );

add_action( 'wp_ajax_options_display_result', function () {
    global $options;
    $table_name = $options->prefix . 'persons';
    if ( wp_verify_nonce( $_POST['nonce'], 'options_display_result' ) ) {
        $task = $_POST['task'];
        if("add-option"== $task){
            //single data save
            $key = "Country";//key unique hote hobe akoi hole result asbe na
            $value = "Bangladesh";
            echo "Result = ". add_option( $key, $value );
        }elseif ("add-array-option"==$task){
            //Array data save
            $key = "Add Array d";
            $value = ['Country'=>"Bangladesh",'Capital'=>'Dhaka'];//a:2:{s:7:"Country";s:10:"Bangladesh";s:7:"Capital";s:5:"Dhaka";} avabe save hbe
            echo "Result = ". add_option( $key, $value )."\n";
            $key = "Add Array JSON";
            $value = json_encode(['Country'=>"Bangladesh",'Capital'=>'Dhaka']);//Json akare save hobe
            echo "Result = ". add_option( $key, $value )."\n";
        }elseif ("get-option"==$task){
            //kono save option show kora
                $key = "Country";
                //je name key(option) deya ache sei name dite hbe
                $result = get_option( $key );
            echo "Result = ". $result."\n";

        }elseif ("get-array-option"==$task){
            $key = "Add Array d";
            $result = get_option( $key );
            print_r(  $result);

            $key = "Add Array JSON";
            $result = json_decode(get_option( $key ));
            print_r( $result);
        }elseif ("option-filter-hook"==$task){
            //hook use ko ইচ্ছা মত পরিবর্তন করা
            $key = "Country";
//option_(key name)
            add_filter('option_Country',function ($value){
                return strtoupper( $value);
            });
            $result = get_option( $key );
            echo "Result = ". $result."\n";

            $key = "Add Array JSON";
            add_filter('option_Add Array JSON',function ($value){
                return json_decode( $value, true);
            });
            $result = get_option( $key );
            print_r( $result);
        }elseif ("update-option"==$task){
            $key = 'capital';
//            $value = 'dhaka';
            $value = 'Dhaka';
            //thakle update korbe na thakle insert korbe
            $result = update_option( $key, $value );
            echo "Result = ". $result."\n";
        }elseif ("update-array-option"==$task){
            $key = "Update_Array";
            $value = ['Country'=>"Bangladesh",'Capital'=>'Dhaka'];
            $updatevalue = ['Country'=>"Nepal",'Capital'=>'kathmandu'];
            echo "Result = ".update_option( $key, $value )."\n";
            echo "Result = ".update_option( $key, $updatevalue )."\n";
        }elseif ("delete-option"==$task){
            $key="Add Array d";
            $result=delete_option( $key );
            echo "Result = ". $result."\n";
        }elseif ("export-option"==$task){

//            $key_normal = ["capital"];
//            $key_array = ['Update_Array'];
//            $key_json = ['Add Array JSON','Add Array'];
//            $exportData = [];
//            foreach ($key_normal as $key ) {
//                $value = get_option( $key );
//                $exportData[$key] = $value;
//            }
//            foreach ($key_array as $key ) {
//                $value = get_option( $key );
//                $exportData[$key] = $value;
//            }
//            foreach ($key_json as $key ) {
//                $value = json_encode(get_option( $key ));
//                $exportData[$key] = $value;
//            }
            $key_group = [
                'normal' => ["capital"],
                'array' => ['Update_Array'],
                'json' => ['Add Array JSON', 'Add Array']
            ];

            $exportData = [];

            foreach ($key_group as $type => $keys) {
                foreach ($keys as $key) {
                    $value = get_option($key);

                    if ($type === "json" && is_array($value)) {
                        $value = json_encode($value); // Ensure JSON encoding only for arrays
                    }

                    $exportData[$key] = $value;
                }
            }

            echo json_encode($exportData); // Output the data as JSON

//            echo base64_encode(json_encode($exportData));
        }elseif ("import-option"==$task){
            $import_data = '{"capital":"Dhaka","Update_Array":{"Country":"Nepal","Capital":"kathmandu"},"Add Array JSON":"{\"Country\":\"Bangladesh\",\"Capital\":\"Dhaka\"}","Add Array":"{\"Country\":\"Bangladesh\",\"Capital\":\"Dhaka\"}"}';
            $array_data = json_decode($import_data,true);
            print_r($array_data);
            foreach ($array_data as $key => $value) {
                update_option($key, $value);
            }
        }
    }
    die( 0 );
} );
//add_filter('option_Country',function ($value){
//    return strtoupper( $value);
//});

add_action( 'admin_menu', function () {
    add_menu_page(
            'Options Demo',
            'Options Demo',
            'manage_options',
            'options-demo',
            'optionsdemo_admin_page'
    );
} );

function optionsdemo_admin_page() {
    ?>
    <div class="container" style="padding-top:20px;">
        <h1>Options Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='add-option'>Add New Option</button>
                    <button class="action-button" data-task='add-array-option'>Add Array Option</button>
                    <button class="action-button" data-task='get-option'>Display Saved Option</button>
                    <button class="action-button" data-task='get-array-option'>Display Option Array</button>
                    <button class="action-button" data-task='option-filter-hook'>Option Filter Hook</button>
                    <button class="action-button" data-task='update-option'>Update Option</button>
                    <button class="action-button" data-task='update-array-option'>Update Array Option</button>
                    <button class="action-button" data-task='delete-option'>Delete Option</button>
                    <button class="action-button" data-task='export-option'>Export Options</button>
                    <button class="action-button" data-task='import-option'>Import Options</button>
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
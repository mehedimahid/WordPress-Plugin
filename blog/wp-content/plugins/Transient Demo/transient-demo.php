<?php
/**
 * Plugin Name: Transient Demo
 * Plugin URI:
 * Description: Demonstration of Transient Methods
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI: https://github.com/mehedimahid
 * License: GPLv2 or later
 *Text Domain: transient-demo
 *Domain Path: /languages/
 */

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_transient-demo' == $hook ) {
        wp_enqueue_style( 'pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css' );
        wp_enqueue_style( 'trd-demo-css', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
        wp_enqueue_script( 'trd-demo-js', plugin_dir_url( __FILE__ ) . "assets/js/main.js", array( 'jquery' ), time(), true );
        $nonce = wp_create_nonce( 'transient_display_result' );
        wp_localize_script(
            'trd-demo-js',
            'plugindata',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => $nonce )
        );
    }
} );


add_action('wp_ajax_transient_display_result',function(){
    global $transient;
    $table_name = $transient->prefix.'persons';
    if(wp_verify_nonce($_POST['nonce'], 'transient_display_result')){
        $task=$_POST['task'];
        switch ($task){
            case'add-transient':
                $key = 'trd_country';
                $value = "Bangladesh";
                echo "Result=". set_transient( $key, $value );
                break;
            case'set-expiry':
                $key = 'trd_capital';
                $value = "Dhaka";
                $expiry = 1*60;
                echo "Result=". set_transient( $key, $value,$expiry );
                break;
            case 'display-transient':
                $key1 = 'trd_country';
                $key2 = 'trd_capital';

                echo "Result1=". get_transient( $key1)."\n";//always result show korbe
                echo "Result2=". get_transient( $key2)."\n";//expiry time thakar karone time sese ar dekhabe na
                break;
            case 'importance':
                 $key = 'trd-temperature-dhaka';
                 $value = 0;
                 set_transient( $key, $value );

                 $result = get_transient( $key );
//
                if($result ==false){
                    echo "temperature not found in dhaka\n";//0 == false
                }else{
                    echo "Today temperature is " . $result. " in dhaka\n";
                }
                if($result ===false){ //0 === not false
                    echo "temperature not found in dhaka\n";
                }else{
                    echo "Today temperature is " . $result. " in dhaka\n";
                }

                 break;
            case 'add-complex-transient':
               global $wpdb;
               $result = $wpdb->get_results("SELECT post_title FROM {$wpdb->prefix}posts order by id desc limit 5",ARRAY_A);
//               print_r($result);
                $key = "trd_letest_post";
                set_transient($key,$result, 60*60);
                $later = get_transient($key);
                print_r($later);
               break;
            case 'transient-filter-hook':
                $key1 = 'trd_country';

                add_filter('pre_transient_trd_country',function(){
                    return "Bangladesh IS MY LOVE";
                });
                echo "Result=". get_transient( $key1)."\n";
                break;
            case "delete-transient":
                $key = 'trd_country';
                echo "Before Delete=". get_transient($key)."\n";
                delete_transient($key);
                echo "After Delete=". get_transient($key)."\n";
                break;
            default:
                echo "Unknown Task.";
                break;
        }
    }
    die(0);
});


add_action( 'admin_menu', function () {
    add_menu_page(
            'Transient Demo',
            'Transient Demo',
            'manage_options',
            'transient-demo',
            'trd_admin_page'
    );
} );

function trd_admin_page() {
    ?>
    <div class="container" style="padding-top:20px;">
        <h1>Options Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='add-transient'>Add New Transient</button>
                    <button class="action-button" data-task='set-expiry'>Set Expiry Time</button>
                    <button class="action-button" data-task='display-transient'>Display Transient</button>
                    <button class="action-button" data-task='importance'>Importance Of ===</button>
                    <button class="action-button" data-task='add-complex-transient'>Add Complex Transient</button>
                    <button class="action-button" data-task='transient-filter-hook'>Transient Filter Hook</button>
                    <button class="action-button" data-task='delete-transient'>Delete Transient</button>
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
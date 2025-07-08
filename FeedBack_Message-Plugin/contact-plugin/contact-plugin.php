<?php

/**
 * Plugin Name:       Contact Plugin
 * Plugin URI:        
 * Description:       A simple plugin that shows a welcome message to new visitors
 * Version:           1.0.0
 * Author:            Mehedi Hasan
 * Author URI:        https://github.com/mehedimahid
 * License:           GPL2
 * Text Domain:       contact-plugin
 * Domain Path:       /languages
 */

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    die( 'This file cannot be accessed directly.' );
}
if(!class_exists("ContactPlugin")){
    define ('pluginDirPath' , plugin_dir_path(__FILE__));

    class ContactPlugin{
        function __construct(){

            require_once pluginDirPath.'/vendor/autoload.php';

            add_action("wp_enqueue_scripts",array($this,'loaded_assets'));

        }
        function loaded_assets(){
            wp_enqueue_style(
                'bootstrap-css',
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
            );
            wp_enqueue_style(
                'simple-contact-form',
                plugin_dir_url(__FILE__).'assets/css/contact-form.css',
                false,
                '1.0',
                'all'
            );
            wp_enqueue_script('contact-form-js',plugin_dir_url(__FILE__).'assets/js/contact-form.js',array('jquery'), '1.0', true);
            wp_localize_script('contact-form-js', 'contactForm', array(
                'nonce' => wp_create_nonce('wp_rest'),
                'url' => get_rest_url(null, 'contact-form/v1/sendfeedback'),

            ));
        }
        function initialize(){

            require_once pluginDirPath.'/includes/options.php';
            require_once pluginDirPath.'/includes/utilities.php';
            require_once pluginDirPath.'/includes/contact-form.php';
        }
    }
    $contactPlugin = new ContactPlugin();
    $contactPlugin->initialize();
}

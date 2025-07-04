<?php
/*
Plugin Name: Simple Contact Form
Plugin URI:
Description: A simple plugin that shows a welcome message to new visitors.
Version: 1.0
Author: Mehedi Hasan
Author URI: https://github.com/mehedimahid
License: GPL2
*/
if(!defined('ABSPATH')){
    echo "What are you trying to do";
    exit();
}
class SimpleContactForm {
    function __construct(){
        add_action('init',array($this,'create_custom_post_type'));
        //assets loaded
        add_action("wp_enqueue_scripts",array($this,'loaded_assets'));
        //add Short Code
        add_shortcode('contact-form',array($this,'contact_form_shortcode'));
        //register rest API
        add_action('rest_api_init', array($this, 'register_rest_api_init'));
    }
    function create_custom_post_type(){
        $arg = array(
            "public"=>true,
            "has_archive"=>true,
            "capability"=>"manage_options",
            "exclude_from_search"=>true,
            "publicly_queryable"=>false,
            'labels'=>array(
                'name'=>"Contact Form",
                'singular_name'=>"Contact Form Entry"
            ),
            'menu_icon'=>'dashicons-media-text',
        );
        register_post_type("simple_contact_form",$arg);
    }
    function loaded_assets(){
        wp_enqueue_style(
            'simple-contact-form',
            plugin_dir_url(__FILE__).'assets/css/simple-contact-form.css',
            false,
            '1.0',
            'all'
        );
//        wp_enqueue_style(
//            'bootstrap-css',
//            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
//        );
        wp_enqueue_script(
            'simple-contact-form',
            plugin_dir_url(__FILE__).'assets/js/simple-contact-form.js',
            array('jquery'),
            '1.0',
            true
        );
        wp_localize_script("simple-contact-form","ContactFormData",array(
            'nonce'    => wp_create_nonce('wp_rest'),
            'rest_url' => get_rest_url(null,"simple-contact-form/v1/contact-us")
        ));

    }
    function contact_form_shortcode()
    {?>

        <div class="simple-contact-form">
            <h2>Email Us</h2>
            <form id="simple-contact-form_form">
                <div class="form-group ">
                    <input type="text" name="name" placeholder="Name" class="form-control">
                </div>
                <div class="form-group ">
                    <input type="email" name="email" placeholder="Email" class="form-control">
                </div>
                <div class="form-group ">
                    <textarea name="message" placeholder="Type Your Message" class="form-control"></textarea>
                </div>
                <div class="form-group ">
                    <button  class="btn btn-success w-100">Send Message</button>
                </div>
            </form>
        </div>
    <?php
    }
    function register_rest_api_init(){
        register_rest_route("simple-contact-form/v1","contact-us",array(
                "methods"=>"POST",
                'callback'=>array($this,'contact_form_callback'),
                "permission_callback" => '__return_true'

        ));
    }
    function contact_form_callback($data){
       $headers = $data->get_headers();
       $params = $data->get_params();
       $nonce = $headers['x_wp_nonce'][0];
       if(!wp_verify_nonce($nonce, 'wp_rest')){
           return new WP_REST_Response("Message Not Sent",422);
       }
       $post_id= wp_insert_post(array(
               'post_type'=>'simple_contact_form',
               'post_status'=>'publish',
                'post_title'=>'Contact Form',

       ));
       if ($post_id){
           return new WP_REST_Response("Thanks for sending message",200);
       }
    }
}
new SimpleContactForm();
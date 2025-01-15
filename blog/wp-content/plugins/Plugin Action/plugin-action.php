<?php

/**
 * Plugin Name: Plugin Link Demo
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Mehedi Hasan
 * Author URI:
 * License: GPLv2 or later
 *Text Domain: plugin-action
 *Domain Path: /languages/
 */
add_action('admin_menu',function(){
    add_menu_page(
        __('Plugin Action', 'plugin-action'),
        __('Plugin Action', 'plugin-action'),
        'manage_options',
        'plugin-action',
        function(){
            ?>
            <h1>Hello World</h1>
            <?php
        }
    );
});

add_action('activated_plugin',function($plugin){
    if(plugin_basename(__FILE__)== $plugin){
        wp_redirect(admin_url("admin.php?page=plugin-action"));
        die();
    };
});
//add action link
add_filter('plugin_action_links_'.plugin_basename(__FILE__),function($links){
    $link = sprintf(
        '<a href="%s" style="color: #ff0000">%s</a>',
        esc_url(admin_url('admin.php?page=plugin-action')),
        esc_html__('Settings', 'plugin-action')
    );
    array_push($links,$link);
//    array_unshift($links,$link);
    return $links;
});

//add row link
add_filter('plugin_row_meta',function ($links,$plugin){
    if(plugin_basename(__FILE__)==$plugin){
        $link = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            esc_url("https://github.com/mehedimahid"),
            esc_html__('GitHub', 'plugin-action')
        );
        array_push($links,$link);
    }
    return $links;
},10,2);





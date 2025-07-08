<?php
add_action('rest_api_init', 'register_rest_api');
add_shortcode( 'contact', 'render_contact_form' );
add_action('init','create_submissins_table');
add_action('add_meta_boxes', 'add_custom_meta_box');

add_filter('manage_submitaded_feedbacks_posts_columns', 'custom_feedback_columns');
add_action('manage_submitaded_feedbacks_posts_custom_column', 'custom_feedback_column_content', 10, 2);

//NOT WORKING
//add_action('admin_init', 'custom_search_setup');

// function custom_search_setup($query){
//     global $typenow;
//     if ($typenow == 'submitaded_feedbacks') {
//         add_filter('posts_search', 'submission_search_override', 10, 2);
//     }
// }
// function submission_search_override($search, $query) {
//     global $wpdb;

//     if ($query->is_main_query() && !empty($query->query['s'])) {
//         $search_term = $query->query['s'];
//         $like = '%' . $wpdb->esc_like($search_term) . '%';

//         $sql = "
//                 OR EXISTS (
//                     SELECT * FROM {$wpdb->postmeta}
//                     WHERE post_id = {$wpdb->posts}.ID
//                     AND meta_key IN ('name', 'email')
//                     AND meta_value LIKE %s
//                 )
//             )
//         ";

//         $search = preg_replace("#\({$wpdb->posts}.post_title[^)]+\)\K#",
//                 $wpdb->prepare($sql,$like),$search);
//     }

//     return $search;
// }

function custom_feedback_columns($columns){
    $columns = [
        'cb' => '<input type="checkbox" />',
        'title' => __('Name', 'contact-plugin'),
        'email' => __('Email', 'contact-plugin'),
        'message' => __('Message', 'contact-plugin'),
        'date' => __('Date', 'contact-plugin'),
    ];
    return $columns;
}
function custom_feedback_column_content($column, $post_id){
    switch($column){
        case 'email':
            echo esc_html(get_post_meta($post_id, 'email', true));
            break;
        case 'message':
            $message =  esc_html(get_post_meta($post_id, 'message', true));
            $excerpt = wp_trim_words($message, 10, '...');
            echo esc_html($excerpt);
            break;
    }
}

function add_custom_meta_box(){
    add_meta_box(
       'custom_contact_fields',
       'Submissins',
       'display_custom_meta_box',
       'submitaded_feedbacks',
    );
}
function display_custom_meta_box(){
    $postMetas = get_post_meta(get_the_ID());
    // unset($postMetas['_edit_lock']);
    // unset($postMetas['_edit_last']);

    // echo '<ul>';
    //     foreach($postMetas as $key =>$value){
    //         echo '<li><strong>'. ucfirst($key) . '</strong> : ' . esc_html($value[0]) . '</li>';
    //     }
    // echo'</ul>';
    echo '<ul>';
        echo '<li><strong>Name</strong> : ' . esc_html( get_post_meta(get_the_ID(), 'name',true)). '</li>';
        echo '<li><strong>Email</strong> : ' . esc_html( get_post_meta(get_the_ID(), 'email',true)) . '</li>';
        echo '<li><strong>Message</strong> : ' . esc_html( get_post_meta(get_the_ID(), 'message',true)) . '</li>';
        // echo '<li><strong>Name</strong> : ' . get_post_meta(get_the_ID(), 'name',true) . '</li>';
        
    echo'</ul>';
}

function create_submissins_table(){
    $arg = [
        'public' => true,
        'menu_position' => 30,
        'publicly_queryable' => false,
        'menu_icon' => 'dashicons-email-alt',
        'has_archive' => true,
        'labels'=>[
            'name'=>'Feedbacks Submissins',
            'singular_name'=>'Feedback Submissin',
            'edit_item'=>'View Submitted Feedback',
        ],
        'capability_type'=>'post',
        'capabilities'=>['create_posts' => false],
        'map_meta_cap'=>true,
        'supports'=>false,
    ];
    register_post_type('submitaded_feedbacks',$arg);
};

function render_contact_form(){
    include pluginDirPath.'/includes/templates/contact-form-temp.php' ;
}

function register_rest_api(){
    register_rest_route("contact-form/v1", 'sendfeedback', array(
        'methods'=>'POST',
        'callback'=>'handle_enquiry',
        "permission_callback" => '__return_true'

    ));
}

function handle_enquiry($data){
     $params = $data->get_params();

     $params_name = sanitize_text_field($params['name']);
     $params_email = sanitize_email($params['email']);  
     $params_message = sanitize_textarea_field($params['message']);
    //  echo $params['name'];
     $getHeaders = $data->get_headers();
     $nonce = $getHeaders['x_wp_nonce'][0];
    // $nonce = 1233344;

     if(!wp_verify_nonce($nonce, 'wp_rest')){
         return new WP_Error('rest_forbidden', __('You are not allowed to do this'), array('status' => 403));
     };
    //  return new WP_Rest_Response('Enquiry received successfully', 200);
    
    

    $headers =[];
    $admin_Name = get_bloginfo('name');
    $admin_Email = get_bloginfo('admin_email');

    $recipients_email = get_plugin_options('recipiens_email');
     if(!$recipients_email){
        $recipients_email = $admin_Email;
     }

    $headers[] = "From: {$admin_Name} <{$admin_Email}>";
    $headers[] = "Reply-To: {$params_name} <{$params_email}>";

    $subject = "New Enquiry from {$params_name}";
    $message = '';
    $message .= "<h1>Message has been send from: {$params_name}</h1>\n";
    
    $postArg = [
        'post_title' => $params_name,
        'post_type' => 'submitaded_feedbacks',
        'post_status' => 'publish',
    ];
    $postId = wp_insert_post($postArg);
    foreach($params as $key => $value){
        $message .= ucfirst($key)." : {$value}\n";
        add_post_meta($postId, $key, sanitize_text_field($value));
    }
    wp_mail(
        // $admin_Email,
        $recipients_email,
        $subject,
        $message,
        $headers
    );
    $confirmation_message ="Thank you for contacting us! We will get back to you soon.";
    if($confirmation_message){
        $confirmation_message = get_plugin_options('recipiens_message');
        $confirmation_message = str_replace('{name}', $params_name, $confirmation_message);
    }
    return new WP_Rest_Response($confirmation_message, 200);

}
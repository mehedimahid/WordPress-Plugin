<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'after_setup_theme', 'load_carbon_field' );
function load_carbon_field() {
    // require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}

add_action( 'carbon_fields_register_fields', 'create_contact_options' );
function create_contact_options() {
    Container::make( 'theme_options', __( 'Contact Form' ) )
        ->set_page_menu_position( 30 )
        ->set_icon('dashicons-format-aside')
        ->add_fields( array(
            Field::make('checkbox',"contact_plugin_active", __('Active'))
                ->set_help_text("Check this box to activate the contact form"),
            Field::make( 'text', 'recipiens_email', __('Email') )
                ->set_attribute( 'placeholder', __('Enter Recipiens Email') )
                ->set_help_text("Please, Enter Recipiens Email address where you want to receive the messages."),
            Field::make( 'textarea', 'recipiens_message', __('Message') )
                ->set_attribute( 'placeholder', __('Enter your message') )
                ->set_help_text("Type the message that you want to send ."),
        ) );
}
<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('after_setup_theme', 'load_carbon_fields');

add_action('carbon_fields_register_fields', 'carbon_attach_theme_options');
add_action('carbon_fields_theme_options_container_saved', 'handle_save_of_container_theme_options');

function load_carbon_fields()
{
    \Carbon_Fields\Carbon_Fields::boot();
}

function carbon_attach_theme_options()
{
    Container::make('theme_options', __('Elitiweb Authentication'))
        ->add_fields([
            Field::make('text', 'crb_text', 'Text Field'),
        ]);
}

function handle_save_of_container_theme_options()
{
    carbon_get_theme_option('crb_text_1');
}

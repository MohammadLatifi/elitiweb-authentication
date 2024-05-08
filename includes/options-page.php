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
            Field::make('html', 'elitiweb_authentication_information_text')
                ->set_html('
                <h1>Get Started:</h1>
                <p>For using this plugin you need to first visit the <a href="https://clerk.com/">Clerk site</a> and make a free acount.</p>
                <p>From your Clerk acount dashboard you need to provide this 2 arguments:</p>
                '),
            Field::make('text', 'clerk_publishable_key', 'Clerk Publishable Key:'),
            Field::make('text', 'your_clerk_domain', 'Your Clerk Domain:'),
            Field::make('html', 'elitiweb_authentication_information_second_text')
                ->set_html('
                <h1>Caution:</h1>
                <p>The plugin with put the user profile button automatically in the Wordpress Menu items so you must first have a Theme </br> That makes Menu like Elementor HelloWord theme. Additionally make sure the you generated a menu after theme installation.</p>
                <p>The plugin will automatically add a sign-in page to your site pages with title \'sign-in\'. if you already have such page you need to remove it.</p>
                '),
        ]);
}

function handle_save_of_container_theme_options()
{
    carbon_get_theme_option('clerk_publishable_key');
}

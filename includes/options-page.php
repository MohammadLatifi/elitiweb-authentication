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
    Container::make('theme_options', __('Elitiweb Clerk'))->set_icon('dashicons-shield')
    ->add_tab(__('Settings'), [
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
    ])->add_tab(__('Restricted Urls'), [
            Field::make('text', 'redirected_after_sign_in', 'Enter the page URL to which the user should be redirected after sign-in:')
            ->set_attribute('placeholder', '/index.php/dashboard'),
            Field::make('text', 'redirected_after_sign_out', 'Enter the page URL to which the user should be redirected after sign-out:')
            ->set_attribute('placeholder', '/'),
            Field::make('html', 'elitiweb_authentication_information_third_text')
            ->set_html('
            <h4>Shortcodes: </h4>
            <p>You can use this shortcodes in your page designs to access the logged-in user information.</p>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 8px; border: 1px solid #ddd;">Description</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Shortcode</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">If you want to retrict access to a page to only logged-in user you need to add this shortcode to your page: </td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[restricted-page]</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">Add the user firstname and use <code>id="elitiweb-clerk-user-firstname"</code> for styling.</td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[user-firstname]</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">Add the user lastname and use <code>id="elitiweb-clerk-user-lastname"</code> for styling.</td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[user-lastname]</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">Add the user fullname and use <code>id="elitiweb-clerk-user-fullname"</code> for styling.</td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[user-fullname]</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">Add the user username and use <code>id="elitiweb-clerk-user-username"</code> for styling.</td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[user-username]</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">Add the user email and use <code>id="elitiweb-clerk-user-email"</code> for styling.</td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[user-email]</strong></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">Add the user image and use <code>id="elitiweb-clerk-user-image"</code> for styling.</td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>[user-image]</strong></td>
                    </tr>
                </tbody>
            </table>

            '),
    ]);
}

function handle_save_of_container_theme_options()
{
    carbon_get_theme_option('clerk_publishable_key');
}

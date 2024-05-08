<?php

/*
 *  @package elitiweb-authentication
 */

/*
    Plugin Name: Elitiweb Authentication
    Plugin URI: https://Elitiweb.com/
    Description: Used by millions, Elitiweb Authentication is one of the best plugins.
    Version: 1.0.0
    Requires at least: 5.8
    Requires PHP: 7.4.0
    Author: Elitiweb Team
    License: GPLv2 or later
    Text Domain: Elitiweb
*/

/*
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

    Copyright 2005-2023 Automattic, Inc.
*/

if (!defined('ABSPATH')) {
    exit('You shouldn\'t be here.');
}

if (!class_exists('ElitiwebAuthentication')) {
    class ElitiwebAuthentication
    {
        public function __construct()
        {
            define('ELITIWEB_AUTHENTICATION_PLUGIN_PATH', __DIR__.'/');

            require_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'vendor/autoload.php';

            add_action('init', [$this, 'create_sign_in_page']);

            add_action('wp_enqueue_scripts', [$this, 'load_assets']);

            add_action('wp_body_open', [$this, 'add_clerk_html_to_body']);

            add_action('wp_footer', [$this, 'load_scripts_in_footer']);

            add_filter('wp_nav_menu_items', [$this, 'custom_menu_items'], 10, 2);
        }

        public function initialize()
        {
            include_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'includes/options-page.php';
        }

        public function custom_menu_items($items, $args)
        {
            $items .= '<li id="elitiweb-authentication-nav-item"></li>';

            return $items;
        }

        public function create_sign_in_page()
        {
            if (!get_page_by_path('sign-in')) {
                $page_args = [
                    'post_title' => 'sign-in',
                    'post_content' => '<div id="sign-in"></div>',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => 'sign-in',
                ];

                // Insert the page into the database
                $page_id = wp_insert_post($page_args);

                // Return the ID of the newly created page
                return $page_id;
            }
        }

        public function load_assets()
        {
            wp_enqueue_style(
                'elitiweb-authentication-css',
                plugin_dir_url(__FILE__).'includes/css/style.css',
                [],
                '1.0.0',
                false);

            if (is_page('sign-in')) {
                wp_enqueue_style(
                    'elitiweb-authentication-sign-in-css',
                    plugin_dir_url(__FILE__).'includes/css/sign-in-style.css', [],
                    '1.0.0',
                    false
                );
            }
        }

        public function load_scripts_in_footer()
        {
            ?>

            <script>
            
            const clerkPublishableKey = '<?php echo carbon_get_theme_option('clerk_publishable_key'); ?>';
            const frontendApiUrl = '<?php echo carbon_get_theme_option('your_clerk_domain'); ?>';
            const version = '@5.2.1';

            const script = document.createElement('script');
            script.setAttribute('data-clerk-publishable-key', clerkPublishableKey);
            script.async = true;
            script.src = '<?php echo plugin_dir_url(__FILE__); ?>'+'node_modules/@clerk/clerk-js/dist/clerk.browser.js';
            const userButton = document.getElementById('user-button');
            const signInButton = document.getElementById('sign-in-button');

            script.addEventListener('load', async function () {
                await window.Clerk.load({
                    signInForceRedirectUrl: '<?php echo home_url(); ?>',
                    signOutForceRedirectUrl: '<?php echo home_url(); ?>',
                    routerPush : ()=>{
                        window.location.current = '<?php echo home_url(); ?>';
                        document.getElementById("elitiweb-authentication-nav-item").innerHTML = `
                            <a href = "<?php echo home_url(); ?>/index.php/sign-in"> Sign in </a>
                        `;
                    }
                }).then(()=>{
                   
                    if (window.Clerk.user) {
                            document.getElementById("elitiweb-authentication-nav-item").innerHTML = `<div id="user-button"></div>`;

                            const userButtonDiv = document.getElementById("user-button");

                            window.Clerk.mountUserButton(userButtonDiv);
                        } else {
                            document.getElementById("elitiweb-authentication-nav-item").innerHTML = `
                                <a href = "<?php echo home_url(); ?>/index.php/sign-in"> Sign in </a>
                            `;

                            const signInDiv = document.getElementById("sign-in");

                            window.Clerk.mountSignIn(signInDiv);
                    }
                })
            });
            document.body.appendChild(script);
            </script>
            <?php
        }

        public function add_clerk_html_to_body()
        {
            echo file_get_contents(ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'includes/templates/clerk-init.html');
        }
    }

    $elitiwebAuthentication = new ElitiwebAuthentication();
    $elitiwebAuthentication->initialize();
}

<?php

/*
 *  @package elitiweb-authentication
 */

/*
    Plugin Name: Elitiweb Clerk
    Plugin URI: https://Elitiweb.com/
    Description: Used by millions, Elitiweb Authentication with Clerk is one of the best plugins.
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

            // add shortcodes:
            add_shortcode('restricted-page', [$this, 'restricted_page_shortcode']);
            add_shortcode('user-firstname', [$this, 'user_firstname_shortcode']);
            add_shortcode('user-lastname', [$this, 'user_lastname_shortcode']);
            add_shortcode('user-fullname', [$this, 'user_fullname_shortcode']);
            add_shortcode('user-username', [$this, 'user_username_shortcode']);
            add_shortcode('user-email', [$this, 'user_email_shortcode']);
            add_shortcode('user-image', [$this, 'user_image_shortcode']);
        }

        public function restricted_page_shortcode()
        {
            ?>
                <input type="hidden" id="elitiweb-input-restricted-page" value='1' />
            <?php
        }

        public function user_firstname_shortcode()
        {
            ?>
            <p id="elitiweb-user-firstname"></p>
        
            <?php
        }

        // Add shortcode [user-lastname]
        public function user_lastname_shortcode()
        {
            ?>
            <p id="elitiweb-user-lastname"></p>
        
            <?php
        }

        // Add shortcode [user-fullname]
        public function user_fullname_shortcode()
        {
            ?>
            <p id="elitiweb-user-fullname"></p>
        
            <?php
        }

        // Add shortcode [user-username]
        public function user_username_shortcode()
        {
            ?>
            <p id="elitiweb-user-username"></p>
        
            <?php
        }

        // Add shortcode [user-email]
        public function user_email_shortcode()
        {
            ?>
            <p id="elitiweb-user-email"></p>
        
            <?php
        }

        // Add shortcode [user-image]
        public function user_image_shortcode()
        {
            ?>
            <img id="elitiweb-user-image" src='#'/>
        
            <?php
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
                    signInForceRedirectUrl: '<?php echo home_url().carbon_get_theme_option('redirected_after_sign_in'); ?>',
                    signOutForceRedirectUrl: '<?php echo home_url().carbon_get_theme_option('redirected_after_sign_out'); ?>',
                    routerPush : ()=>{
                        window.location.assign('<?php echo home_url().carbon_get_theme_option('redirected_after_sign_out'); ?>');
                        document.getElementById("elitiweb-authentication-nav-item").innerHTML = `
                            <a href = "<?php echo home_url(); ?>/index.php/sign-in"> Sign in </a>
                        `;
                    }
                }).then(()=>{
                   
                    if (window.Clerk.user) {
                            document.getElementById("elitiweb-authentication-nav-item").innerHTML = `<div id="user-button"></div>`;

                            const userButtonDiv = document.getElementById("user-button");

                            window.Clerk.mountUserButton(userButtonDiv);

                            const elitiweb_user_firstname = document.getElementById("elitiweb-user-firstname");
                            const elitiweb_user_lastname = document.getElementById("elitiweb-user-lastname");
                            const elitiweb_user_fullname = document.getElementById("elitiweb-user-fullname");
                            const elitiweb_user_username = document.getElementById("elitiweb-user-username");
                            const elitiweb_user_email = document.getElementById("elitiweb-user-email");
                            const elitiweb_user_image = document.getElementById("elitiweb-user-image");

                            if(elitiweb_user_firstname !== null) elitiweb_user_firstname.innerHTML = window.Clerk.user?.firstName;
                            if(elitiweb_user_lastname !== null) elitiweb_user_lastname.innerHTML = window.Clerk.user?.lastName;
                            if(elitiweb_user_fullname !== null) elitiweb_user_fullname.innerHTML = window.Clerk.user?.fullName;
                            if(elitiweb_user_username !== null) elitiweb_user_username.innerHTML = window.Clerk.user?.username;
                            if(elitiweb_user_email !== null) elitiweb_user_email.innerHTML = window.Clerk.user?.primaryEmailAddress;
                            if(elitiweb_user_image !== null) elitiweb_user_image.src = window.Clerk.user?.imageUrl;
                           
                        } else {
                            document.getElementById("elitiweb-authentication-nav-item").innerHTML = `
                                <a href = "<?php echo home_url(); ?>/index.php/sign-in"> Sign in </a>
                            `;

                            const signInDiv = document.getElementById("sign-in");

                            window.Clerk.mountSignIn(signInDiv);

                            const isRestrictedPage = document.getElementById("elitiweb-input-restricted-page");

                            if(isRestrictedPage !== null) {
                                document.body.innerHTML = `

                                    <div class='restricted-page-content'>
                                        <h1>Restricted Page</h1>
                                        <p>To access this page, you need to <a href = "<?php echo home_url(); ?>/index.php/sign-in">Sign in</a> first. </p>
                                    </div>
                                `;
                            }
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

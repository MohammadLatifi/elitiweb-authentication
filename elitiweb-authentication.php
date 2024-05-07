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

            add_action('wp_body_open', [$this, 'add_clerk_html_to_body']);

            add_action('wp_footer', [$this, 'load_scripts_in_footer']);
        }

        public function initialize()
        {
            include_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'includes/utilities.php';
            include_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'includes/options-page.php';
        }

        public function load_scripts_in_footer()
        {
            ?>

            <script>
            
            const clerkPublishableKey = '<?php echo carbon_get_theme_option('clerk_publishable_key'); ?>';
            const frontendApiUrl = '<?php echo carbon_get_theme_option('your_clerk_domain'); ?>';
            const version = '@5.2.1'; // Set to appropriate version

            const script = document.createElement('script');
            script.setAttribute('data-clerk-publishable-key', clerkPublishableKey);
            script.async = true;
            script.src = '<?php echo plugin_dir_url(__FILE__); ?>'+'node_modules/@clerk/clerk-js/dist/clerk.browser.js';
            const userButton = document.getElementById('user-button');
            const signInButton = document.getElementById('sign-in-button');

            // Adds listener to initialize ClerkJS after it's loaded
            script.addEventListener('load', async function () {
                await window.Clerk.load().then(()=>{
                    if (window.Clerk.user) {
                        document.getElementById("app").innerHTML = `
                            <div id="user-button"></div>
                        `;

                        const userButtonDiv = document.getElementById("user-button");

                        window.Clerk.mountUserButton(userButtonDiv);
                        } else {
                        document.getElementById("app").innerHTML = `
                            <div id="sign-in"></div>
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

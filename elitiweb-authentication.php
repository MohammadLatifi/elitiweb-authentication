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

// if (!defined('ABSPATH')) {
//     exit('You shouldn\'t be here.');
// }

if (!class_exists('ElitiwebAuthentication')) {
    class ElitiwebAuthentication
    {
        public function __construct()
        {
            define('ELITIWEB_AUTHENTICATION_PLUGIN_PATH', __DIR__.'/');
            require_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'vendor/autoload.php';
        }

        public function initialize()
        {
            include_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'includes/utilities.php';
            include_once ELITIWEB_AUTHENTICATION_PLUGIN_PATH.'includes/options-page.php';
        }
    }

    $elitiwebAuthentication = new ElitiwebAuthentication();
    $elitiwebAuthentication->initialize();
}

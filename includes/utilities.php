<?php

function setup_database()
{
    // https://codex.wordpress.org/Creating_Tables_with_Plugins:

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix.'elitiweb_authentication_clerk_credentials';
    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    name tinytext NOT NULL,
    text text NOT NULL,
    url varchar(55) DEFAULT '' NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function insert_credentials_to_database()
{
    // https://codex.wordpress.org/Creating_Tables_with_Plugins:

    global $wpdb;

    $table_name = $wpdb->prefix.'liveshoutbox';

    $wpdb->insert(
        $table_name,
        [
            'time' => current_time('mysql'),
            'name' => $welcome_name,
            'text' => $welcome_text,
        ]
    );
}

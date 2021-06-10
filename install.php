<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once(__DIR__.'/form/SouinConfiguration.php');

global $souin_db_version;
$souin_db_version = '1.0';

function souin_install() {
    global $wpdb;
    global $souin_db_version;

    $table_name = $wpdb->prefix . 'souin';

    $charset_collate = $wpdb->get_charset_collate();

    dbDelta(<<<SQL
CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  configuration text NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate
SQL
);

    $c = new SouinConfiguration();
    $wpdb->insert(
        $table_name,
        [
            'configuration' => \serialize($c),
        ]
    );

    add_option( 'souin_db_version', $souin_db_version );
}

function souin_update_db_check() {
    global $souin_db_version;
    if ( get_site_option( 'souin_db_version' ) != $souin_db_version ) {
        souin_install();
    }
}
add_action( 'plugins_loaded', 'souin_update_db_check' );
register_activation_hook( __FILE__, 'souin_install' );

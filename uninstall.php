<?php

// if uninstall.php is not called by WordPress, die
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    die;
}

delete_option( 'apexchat_settings_group' );
delete_option( 'apexchat_version' );

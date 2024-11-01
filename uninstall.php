<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

if(!$vplpro) {
    // Uninstall all the mbVPlayer settings
    delete_option('mbVPlayer_version');
    delete_option('mbVPlayer_is_active');
    delete_option('mbVPlayer_video_url');
    delete_option('mbVPlayer_video_page');
}

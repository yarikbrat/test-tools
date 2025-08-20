<?php

if ( !is_user_logged_in() || !current_user_can( 'administrator' ) ) {
    wp_redirect( home_url() );
    exit;
}

// Code...
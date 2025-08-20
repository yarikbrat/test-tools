<?php

/** Action example */
add_action( 'wp_ajax_nopriv_load_more', 'load_more' );
add_action( 'wp_ajax_load_more', 'load_more' );

function load_more() {
    $ajax_response = [];
    $ajax_response[ 'success' ] = 0;
    $ajax_response[ 'message' ] = '';

    // Keys that can be used in the action
    $fillable = [ 'page', 'token' ];

    $data = wcl_load( $_REQUEST, $fillable );
    $token = sanitize_text_field( $data[ 'token' ] );

    // reCAPTCHA validation
    if ( !wcl_captcha_validation( $token ) ) {
        // Captcha validation failed
        $ajax_response[ 'success' ] = 0;
        $ajax_response[ 'message' ] = __( 'Oops, it seems like our security system thinks you are a robot. Please, contact us or try again later.', 'sitedomain' );

        echo json_encode( $ajax_response );
        wp_die();
    }

    // WordPress Nonces validation
    if ( !isset( $data[ 'load_more_wpnonce' ] ) || !wp_verify_nonce( $data[ 'load_more_wpnonce' ], 'load_more' ) ) {
        // WP_Nonce validation failed
        $ajax_response[ 'success' ] = 0;
        $ajax_response[ 'message' ] = __( 'Oops, it seems like our security system thinks you are a robot. Please, contact us or try again later.', 'sitedomain' );

        echo json_encode( $ajax_response );
        wp_die();
    }

    // Your code here...

    wp_reset_postdata();

    echo json_encode( $ajax_response );
    wp_die();
}
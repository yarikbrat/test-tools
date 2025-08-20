<?php

function wcl_dump( $arr ) {
    echo '<pre>';
    print_r( $arr );
    echo '</pre>';
}


function wcl_load( $input_data, $fillable = [] ) {
    $data = [];
    foreach ( $input_data as $k => $v ) {
        if ( in_array( $k, $fillable ) ) {
            $data[ $k ] = trim( $v );
        }
    }
    return $data;
}


function wcl_clean_phone_number( $phone_number ) {
    $phone_number = preg_replace( '/\s+/', '', $phone_number );
    $phone_number = preg_replace( '/\(|\)|\-|\\+/', '', $phone_number );

    return $phone_number;
}


function wcl_mail( $email, $subject, $message ) {
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . SITE_NAME . ' <' . EMAIL_SENDER . '>'
    );

    return wp_mail( $email, $subject, $message, $headers );
}


function wcl_error_mail( $subject, $message ) {
    return wcl_mail( WCL_CONTACT_MAIL, $subject, $message );
}


function wcl_captcha_validation( $token ) {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = RECAPTCHA_SECRET_KEY;

    $response = wp_remote_post( $recaptcha_url, array(
        'body' => array(
            'secret' => $recaptcha_secret,
            'response' => $token,
        )
    ) );

    $response_body = wp_remote_retrieve_body( $response );
    $result = json_decode( $response_body );

    return ( $result->success == true && $result->score >= 0.5 );
}


/** Universal function to register custom post types */
function wcl_register_post_type( $singular_name, $plural_name, $args = array() ) {
    // Default labels
    $labels = [
        'name' => $plural_name,
        'singular_name' => $singular_name,
        'menu_name' => $plural_name,
        'name_admin_bar' => $singular_name,
        'add_new' => 'Add New',
        'add_new_item' => 'Add New ' . $singular_name,
        'new_item' => 'New ' . $singular_name,
        'edit_item' => 'Edit ' . $singular_name,
        'view_item' => 'View ' . $singular_name,
        'all_items' => 'All ' . $plural_name,
        'search_items' => 'Search ' . $plural_name,
        'parent_item_colon' => 'Parent ' . $plural_name . ':',
        'not_found' => 'No ' . $plural_name . ' found.',
        'not_found_in_trash' => 'No ' . $plural_name . ' found in Trash.',
    ];

    // Default arguments for custom post type
    $default_args = [
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => array( 'title', 'editor', 'excerpt' ),
        'show_in_rest' => true,
    ];

    // Merge passed args with default args
    $args = array_merge( $default_args, $args );

    // Generate slug
    $slug = strtolower( str_replace( ' ', '-', $singular_name ) );

    // Register the custom post type
    register_post_type( $slug, $args );
}


/** Universal function to register custom taxonomy */
function wcl_register_taxonomy( $name, $object_type, $singular_name, $plural_name, $args = array() ) {
    // Default labels
    $labels = [
        'name' => $plural_name,
        'singular_name' => $singular_name,
        'search_items' => 'Search ' . $singular_name,
        'all_items' => 'All ' . $plural_name,
        'view_item ' => 'View ' . $singular_name,
        'parent_item' => 'Parent ' . $singular_name,
        'parent_item_colon' => 'Parent ' . $singular_name . ':',
        'edit_item' => 'Edit ' . $singular_name,
        'update_item' => 'Update ' . $singular_name,
        'add_new_item' => 'Add New ' . $singular_name,
        'new_item_name' => 'New ' . $singular_name . ' Name',
        'menu_name' => $plural_name,
        'back_to_items' => '← Back to ' . $plural_name,

    ];

    // Default arguments for custom taxonomy
    $default_args = [
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'public' => false,
        'query_var' => true,
        'show_admin_column' => true,
    ];

    // Merge passed args with default args
    $args = array_merge( $default_args, $args );

    // Register the custom taxonomy
    register_taxonomy( $name, $object_type, $args );
}


/**
 * Generates HTML markup for a video.
 *
 * @param int $id The attachment ID (video).
 * @param array $options An array of parameters:
 *     - 'class' (string): Adds a CSS class to the <video> tag.
 *     - 'params' (string): A string of parameters for the video (e.g., 'autoplay muted loop playsinline').
 *     - 'width' (int|string): The width of the video (in pixels).
 *     - 'height' (int|string): The height of the video (in pixels).
 *
 * @return string The HTML markup for the video.
 */
function wcl_get_attachment_video( $id, $attr = '' ) {
    $mime_type = get_post_mime_type( $id );
    $url = wp_get_attachment_url( $id );

    $html = '';

    if ( strpos( $mime_type, 'video/' ) !== 0 ) {
        return;
    }

    if ( $url ) {
        $class = !empty( $attr[ 'class' ] ) ? ' class="' . esc_attr( $attr[ 'class' ] ) . '"' : '';
        $width = !empty( $attr[ 'width' ] ) ? ' width="' . esc_attr( $attr[ 'width' ] ) . '"' : '';
        $height = !empty( $attr[ 'height' ] ) ? ' height="' . esc_attr( $attr[ 'height' ] ) . '"' : '';
        $params = !empty( $attr[ 'params' ] ) ? ' ' . esc_attr( $attr[ 'params' ] ) : '';

        $html = '<video' . $class . $params . $width . $height . '>';
        $html .= '<source src="' . esc_url( $url ) . '" type="' . esc_attr( $mime_type ) . '">';
        $html .= 'Your browser doesn\'t support HTML5 video tag.';
        $html .= '</video>';
    }

    return $html;
}
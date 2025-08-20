<?php
/**
 * Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

// Example of getting a field from ACF
// $title = get_field( 'content' )[ 'left' ][ 'title' ];

// Support custom "anchor" values.
$anchor = '';
if ( !empty( $block[ 'anchor' ] ) ) {
    $anchor = 'id="' . esc_attr( $block[ 'anchor' ] ) . '" ';
}
?>

<section <?= $anchor; ?>class="wcl-example">
    <div class="wcl-container">
        <div class="wcl-example__content">
            <h1>Hello Block!</h1>
        </div>
    </div>
</section>

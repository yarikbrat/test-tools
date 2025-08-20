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
if (!empty($block['anchor'])) {
  $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

?>

<section class="wcl-gallery">
  <div class="wcl-container">
    <div class="wcl-gallery__content-wrapper">
      <a href="<?php echo get_template_directory_uri() . '/assets/img/generated-image-2464.jpg' ?>" data-fancybox="gallery" data-caption="caption 1">
        <img src="<?php echo get_template_directory_uri() . '/assets/img/generated-image-2464.jpg' ?>" alt="">
      </a>
      <a href="<?php echo get_template_directory_uri() . '/assets/img/generated-image-4372.jpg' ?>" data-fancybox="gallery" data-caption="caption 2">
        <img src="<?php echo get_template_directory_uri() . '/assets/img/generated-image-4372.jpg' ?>" alt="">
      </a>
      <a href="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5361.jpg' ?>" data-fancybox="gallery" data-caption="caption 3">
        <img src="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5361.jpg' ?>" alt="">
      </a>
      <a href="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5865.jpg' ?>" data-fancybox="gallery" data-caption="caption 4">
        <img src="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5865.jpg' ?>" alt="">
      </a>
      <a href="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5913.jpg' ?>" data-fancybox="gallery" data-caption="caption 5">
        <img src="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5913.jpg' ?>" alt="">
      </a>
      <a href="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5969.jpg' ?>" data-fancybox="gallery" data-caption="caption 6">
        <img src="<?php echo get_template_directory_uri() . '/assets/img/generated-image-5969.jpg' ?>" alt="">
      </a>
    </div>
  </div>
</section>
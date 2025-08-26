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

$is_preview_mode = (isset($block['example']) && !empty($block['mode']) && $block['mode'] === 'preview');

if ($is_preview_mode) {
  $theme_dir = wp_normalize_path(get_template_directory());
  $current_dir = wp_normalize_path(__DIR__);
  $theme_url = get_template_directory_uri();

  $relative_path = str_replace($theme_dir, '', $current_dir);
  $relative_path = ltrim($relative_path, '/\\');

  $block_url = trailingslashit($theme_url) . str_replace('\\', '/', $relative_path);

  $preview_img_url = trailingslashit($block_url) . 'preview.png';

  echo '<img src="' . esc_url($preview_img_url) . '" alt="preview" style="width:100%; height:auto;">';

  return;
}




// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) {
  $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

$title = get_field('title') ? get_field('title') : 'Your title here...';



?>

<section <?= $anchor; ?>class="wcl-hero">
  <div class="wcl-container">
    <h1 class="hero-test"><?php echo $title; ?></h1>
  </div>

</section>
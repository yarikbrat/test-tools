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

$image_id = get_field('background_image');



?>

<section <?= $anchor; ?>class="wcl-hero">
  <div class="wcl-container">
    <div class="wcl-hero__wrapper">
      <h1 class="wcl-hero__title">Title</h1>
      <p class="wcl-hero__description">Description</p>
      <div class="wcl-hero__search-wrapper">
        <input type="text" name="hero-search" id="hero-search" class="wcl-hero__search-input" placeholder="Search for AI tools...">
        <button class="wcl-hero__search-button">Search</button>
        <div class="wcl-hero__autocomplete-wrapper" id="hero-autocomplete"></div>
      </div>
    </div>
  </div>
  <?php if ($image_id): ?>
    <div class="wcl-hero__image-wrapper">
      <picture>
        <source media="(max-width: 768px)" srcset="<?= wp_get_attachment_image_url($image_id, 'large') ?>">
        <source media="(max-width: 1440px)" srcset="<?= wp_get_attachment_image_url($image_id, 'large') ?>">
        <img
          src="<?= wp_get_attachment_image_url($image_id, 'full') ?>"
          alt="Hero"
          fetchpriority="high"
          decoding="async"
          class="wcl-hero__image"
          style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
      </picture>

    </div>
  <?php endif; ?>
</section>
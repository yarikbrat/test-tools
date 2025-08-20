<?php

wp_enqueue_style(
  'wcl-acf-test-block-css',
  get_template_directory_uri() . '/template-parts/blocks/test-acf-block/style/style.min.css',
  [],
  WCL_THEME_VERSION
);

wp_enqueue_script(
  'wcl-acf-test-block-js',
  get_template_directory_uri() . '/template-parts/blocks/test-acf-block/script.js',
  ['jquery'],
  WCL_THEME_VERSION,
  true
);


$title = get_field('title') ?: 'Default Title';
// $text  = get_field('text') ?: 'Default text';
?>

<div class="wcl-acf-test-block">
  <h2 class="wcl-acf-test-block__title"><?php echo $title; ?></h2>
  <p class="wcl-acf-test-block__description">1234</p>
</div>
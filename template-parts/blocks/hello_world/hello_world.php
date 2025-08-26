<?php

/**
 * Hello World Block Template
 */
if (isset($block['data']['preview_image'])) {
  echo '<figure style="text-align:center; margin:0;">';
  echo '<img src="' . esc_url($block['data']['preview_image']) . '" alt="Block preview">';
  echo '</figure>';
} elseif (isset($block['data']['preview_text'])) {
  echo '<div class="hello-world-preview" style="padding:20px; background:#eee; border:1px dashed #ccc; text-align:center;">';
  echo esc_html($block['data']['preview_text']);
  echo '</div>';
} else {


  $img_id = get_field('img');
?>
  <div class="hello-world-block">
    <?php print_field('title', '<h3>', '</h3>', 'Hello World!'); ?>
    <p><?php print_field('desc', '', '', 'This is a test block rendered on the frontend.'); ?></p>
    <p><?php print_field('field', '', '', 'This is a test block rendered on the frontend.'); ?></p>
    <?php if ($img_id) : ?>
      <div class="img-wrap">
        <?php echo wp_get_attachment_image($img_id, 'thumbnail'); ?>
      </div>
    <?php endif; ?>
  </div>
<?php
}

<?php

$anchor = '';
if (!empty($block['anchor'])) {
  $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

$posts = get_posts([
  'post_type'      => 'compare',
  'posts_per_page' => 4,
]);

if ($posts): ?>
  <div class="wcl-compare-block container mb-3 pb-3">
    <div class="row g-3">
      <?php foreach ($posts as $post):
        setup_postdata($post);

        $tool_1 = get_field('tool_1', $post->ID);
        $tool_2 = get_field('tool_2', $post->ID);

        if (!$tool_1 || !$tool_2) continue;

        $img1 = get_the_post_thumbnail_url($tool_1->ID, 'medium');
        $img2 = get_the_post_thumbnail_url($tool_2->ID, 'medium');
      ?>
        <div class="col-md-3">
          <a href="<?php echo get_permalink($post); ?>" class="card h-100 text-decoration-none">
            <div class="card-img-top d-flex p-2 gap-0">
              <?php if ($img1): ?>
                <img src="<?php echo esc_url($img1); ?>" alt="" class="img-fluid w-50">
              <?php endif; ?>
              <?php if ($img2): ?>
                <img src="<?php echo esc_url($img2); ?>" alt="" class="img-fluid w-50">
              <?php endif; ?>
            </div>
            <div class="card-body text-center">
              <h5 class="card-title mb-0"><?php echo get_the_title($post); ?></h5>
            </div>
          </a>
        </div>
      <?php endforeach;
      wp_reset_postdata(); ?>
    </div>
    <div class="text-center mt-4">
      <a href="<?php echo site_url('/compare-tools'); ?>" class="btn btn-primary">More</a>
    </div>
  </div>
<?php endif; ?>
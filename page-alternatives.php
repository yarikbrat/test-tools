<?php

$slug = get_query_var('tool');
if (empty($slug)) {
  wp_redirect(home_url());
  exit;
}

get_header();

$orig_post = get_page_by_path($slug, OBJECT, 'ai-tool');
if (!$orig_post) {
  echo '<p class="text-center">Original tool not found.</p>';
  get_footer();
  exit;
}

$orig_post_id = $orig_post->ID;
$orig_title = get_the_title($orig_post_id);

$terms = get_the_terms($orig_post_id, 'ai_tool_category');
$orig_term_ids = $terms && !is_wp_error($terms) ? wp_list_pluck($terms, 'term_id') : [];

if (empty($orig_term_ids)) {
  echo '<p class="text-center">No categories found.</p>';
  get_footer();
  exit;
}

$paged = max(1, get_query_var('paged'));

$args = [
  'post_type' => 'ai-tool',
  'post__not_in' => [$orig_post_id],
  'tax_query' => [[
    'taxonomy' => 'ai_tool_category',
    'field' => 'term_id',
    'terms' => $orig_term_ids,
    'operator' => 'IN',
    'include_children' => false,
  ]],
  'posts_per_page' => 3,
  'paged' => $paged,
];

$wp_query = new WP_Query($args);
?>

<main id="wcl-page-content" class="wcl-page-content">
  <div class="wcl-container">
    <h1 class="text-center"><?php echo esc_html($orig_title); ?> Alternatives</h1>

    <?php if ($wp_query->have_posts()): ?>
      <div class="wcl-related-tools mt-5">
        <div class="row mb-2">
          <?php while ($wp_query->have_posts()):
            $wp_query->the_post(); ?>
            <div class="col-12 col-sm-6 col-lg-4 d-flex">
              <div class="card w-100 h-100 shadow-lg">
                <?php if (has_post_thumbnail()): ?>
                  <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php else: ?>
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/no-image.webp" class="card-img-top" alt="No image">
                <?php endif; ?>

                <?php
                $post_terms = get_the_terms(get_the_ID(), 'ai_tool_category');
                if (!empty($post_terms) && !is_wp_error($post_terms)): ?>
                  <div class="card-body pt-3 pb-0">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                      <?php foreach ($post_terms as $term): ?>
                        <a href="<?php echo esc_url(get_term_link($term)); ?>" class="badge bg-primary text-decoration-none">
                          <?php echo esc_html($term->name); ?>
                        </a>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>

                <div class="card-body">
                  <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark w-100 h-100">
                    <h5 class="card-title text-center"><?php the_title(); ?></h5>
                    <?php if ($excerpt = get_the_excerpt()): ?>
                      <p class="card-text text-center"><?php echo $excerpt; ?></p>
                    <?php endif; ?>
                  </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <div class="pagination d-flex justify-content-center">
          <?php
          the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => '« Previous',
            'next_text' => 'Next »',
            'class'     => 'justify-content-center',
          ]);
          ?>
        </div>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else: ?>
      <p class="text-center">No similar tools found.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
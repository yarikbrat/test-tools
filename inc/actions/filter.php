<?php

function wcl_get_categories_by_filters()
{
  if (
    !isset($_POST['test_tools_wpnonce']) ||
    !wp_verify_nonce($_POST['test_tools_wpnonce'], 'test_tools')
  ) {
    wp_send_json_error(['message' => 'Nonce verification failed']);
    wp_die();
  }

  $use_cases = !empty($_POST['use_case']) ? array_map('intval', $_POST['use_case']) : [];
  $integrations = !empty($_POST['integration']) ? array_map('intval', $_POST['integration']) : [];

  $tax_query = [];

  if (!empty($use_cases)) {
    $tax_query[] = [
      'taxonomy' => 'ai_tool_use_case',
      'field'    => 'term_id',
      'terms'    => $use_cases,
    ];
  }

  if (!empty($integrations)) {
    $tax_query[] = [
      'taxonomy' => 'ai_tool_integration',
      'field'    => 'term_id',
      'terms'    => $integrations,
    ];
  }

  if (!empty($tax_query)) {
    $tax_query['relation'] = 'AND';
  }

  $query = new WP_Query([
    'post_type'      => 'ai-tool',
    'posts_per_page' => -1,
    'tax_query'      => $tax_query,
  ]);

  if (!$query->have_posts()) {
    wp_send_json_success([
      'categories'           => [],
      'html_blocks'          => [],
      'selected_use_cases'   => $use_cases,
      'selected_integrations' => $integrations,
    ]);
  }

  $grouped = [];

  foreach ($query->posts as $post) {
    $post_id    = $post->ID;
    $post_title = get_the_title($post_id);
    $permalink  = get_permalink($post_id);
    $thumbnail  = get_the_post_thumbnail_url($post_id, 'medium') ?: get_template_directory_uri() . '/assets/img/no-image.webp';
    $excerpt    = get_the_excerpt($post_id);
    $terms      = get_the_terms($post_id, 'ai_tool_category');

    $use_case_terms = get_the_terms($post_id, 'ai_tool_use_case');
    $use_case_names = ($use_case_terms && !is_wp_error($use_case_terms)) ? wp_list_pluck($use_case_terms, 'name') : [];

    $integration_terms = get_the_terms($post_id, 'ai_tool_integration');
    $integration_names = ($integration_terms && !is_wp_error($integration_terms)) ? wp_list_pluck($integration_terms, 'name') : [];

    if ($terms && !is_wp_error($terms)) {
      $added_parents = [];
      $term_names = wp_list_pluck($terms, 'name');

      foreach ($terms as $term) {
        $parent_id = $term->parent ?: $term->term_id;

        if (in_array($parent_id, $added_parents, true)) {
          continue;
        }

        if (!isset($grouped[$parent_id])) {
          $grouped[$parent_id] = [];
        }

        $grouped[$parent_id][] = [
          'post_id'           => $post_id,
          'post_title'        => $post_title,
          'post_permalink'    => $permalink,
          'post_thumbnail'    => $thumbnail,
          'post_excerpt'      => $excerpt,
          'post_categories'   => $term_names,
          'post_use_cases'    => $use_case_names,
          'post_integrations' => $integration_names,
        ];

        $added_parents[] = $parent_id;
      }
    }
  }

  $html_blocks = [];

  foreach ($grouped as $parent_id => $posts) {
    ob_start();
?>
    <div class="row g-4" data-term-id="<?php echo esc_attr($parent_id); ?>">
      <?php foreach ($posts as $post): ?>
        <div class="col-12 col-sm-6 col-lg-4 d-flex">
          <div class="card w-100 h-100 shadow-lg">
            <img src="<?php echo esc_url($post['post_thumbnail']); ?>" class="card-img-top" alt="<?php echo esc_attr($post['post_title']); ?>">
            <div class="card-body pt-3 pb-0">
              <div class="d-flex flex-wrap gap-2 mb-2">
                <?php foreach ($post['post_categories'] as $cat_name): ?>
                  <span class="badge bg-primary text-decoration-none"><?php echo esc_html($cat_name); ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="card-body">
              <a href="<?php echo esc_url($post['post_permalink']); ?>" class="text-decoration-none text-dark w-100 h-100">
                <h5 class="card-title mb-2"><?php echo esc_html($post['post_title']); ?></h5>
                <?php if (!empty($post['post_excerpt'])): ?>
                  <p class="card-text"><?php echo esc_html($post['post_excerpt']); ?></p>
                <?php endif; ?>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
<?php
    $html_blocks[$parent_id] = ob_get_clean();
  }

  $response = [];

  foreach ($grouped as $parent_id => $posts) {
    $response[] = [
      'data-term-id' => $parent_id,
      'posts'        => $posts,
    ];
  }

  wp_send_json_success([
    'categories'            => $response,
    'html_blocks'           => $html_blocks,
    'selected_use_cases'    => $use_cases,
    'selected_integrations' => $integrations,
  ]);
}




add_action('wp_ajax_filter', 'wcl_get_categories_by_filters');
add_action('wp_ajax_nopriv_filter', 'wcl_get_categories_by_filters'); { {
  }
}

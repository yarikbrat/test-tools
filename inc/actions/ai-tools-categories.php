<?php

function show_ai_tools_categories()
{
  $ajax_response = [
    'success' => 0,
    'message' => '',
    'data'    => '',
    'posts'   => [],
  ];

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
    $ajax_response['message'] = 'Nonce is missing or invalid';
    wp_send_json($ajax_response);
    exit;
  }

  $id        = $_POST['id'] ?? null;
  $parent_id = isset($_POST['parent_id']) && is_numeric($_POST['parent_id']) ? intval($_POST['parent_id']) : null;

  $use_cases    = !empty($_POST['use_case']) ? array_map('intval', $_POST['use_case']) : [];
  $integrations = !empty($_POST['integration']) ? array_map('intval', $_POST['integration']) : [];

  if ($id === 'more') {
    $ajax_response['success'] = 1;
    $ajax_response['data'] = '<div class="col-12 text-center"><p>"More" not implemented yet.</p></div>';
    wp_send_json($ajax_response);
    exit;
  }

  if (is_numeric($id)) {
    $term_ids = [(int) $id];
  } elseif ($id === 'all' && $parent_id !== null) {
    $child_terms = get_terms([
      'taxonomy'   => 'ai_tool_category',
      'hide_empty' => false,
      'parent'     => $parent_id,
      'fields'     => 'ids',
    ]);

    $term_ids = $child_terms;
    $term_ids[] = $parent_id;
    $term_ids = array_unique($term_ids);
  } else {
    $ajax_response['message'] = 'Invalid parameters';
    wp_send_json($ajax_response);
    exit;
  }

  $tax_query = [
    'relation' => 'AND',
    [
      'taxonomy' => 'ai_tool_category',
      'field'    => 'term_id',
      'terms'    => $term_ids,
    ],
  ];

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

  $query = new WP_Query([
    'post_type'      => 'ai-tool',
    'posts_per_page' => -1,
    'tax_query'      => $tax_query,
  ]);

  $titles = [];

  ob_start();
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $titles[] = get_the_title();
?>
      <div class="col-12 col-sm-6 col-lg-4 d-flex">
        <div class="card w-100 h-100 shadow-lg">
          <?php if (has_post_thumbnail()): ?>
            <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
          <?php else: ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/no-image.webp" class="card-img-top" alt="Default thumbnail">
          <?php endif; ?>

          <div class="card-body pt-3 pb-0">
            <div class="d-flex flex-wrap gap-2 mb-2">
              <?php
              $post_terms = get_the_terms(get_the_ID(), 'ai_tool_category');
              if ($post_terms && !is_wp_error($post_terms)) {
                foreach ($post_terms as $term) {
                  echo '<a href="' . esc_url(get_term_link($term)) . '" class="badge bg-primary text-decoration-none">' . esc_html($term->name) . '</a>';
                }
              }
              ?>
            </div>
          </div>

          <div class="card-body">
            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark w-100 h-100">
              <h5 class="card-title mb-2"><?php the_title(); ?></h5>
              <p class="card-text"><?php echo get_the_excerpt(); ?></p>
            </a>
          </div>
        </div>
      </div>
<?php
    }
  } else {
    echo '<div class="col-12 text-center"><p>No tools found for this category and filters.</p></div>';
  }
  wp_reset_postdata();

  $ajax_response['success'] = 1;
  $ajax_response['data']    = ob_get_clean();
  $ajax_response['posts']   = $titles;
  $ajax_response['tax-query'] = $tax_query;

  wp_send_json($ajax_response);
}

add_action('wp_ajax_show_ai_tools_categories', 'show_ai_tools_categories');
add_action('wp_ajax_nopriv_show_ai_tools_categories', 'show_ai_tools_categories');

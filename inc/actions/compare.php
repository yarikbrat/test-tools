<?php
// function autocomplete_first_tool()
// {
//   $ajax_response = [
//     'success' => 0,
//     'message' => '',
//     'html'    => '',
//   ];

//   if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
//     $ajax_response['message'] = 'Nonce is missing or invalid';
//     wp_send_json($ajax_response);
//     exit;
//   }

//   $query = trim($_POST['query'] ?? '');
//   if (empty($query)) {
//     $ajax_response['message'] = 'Query is empty';
//     wp_send_json($ajax_response);
//   }

//   $args = [
//     'post_type'      => 'ai-tool',
//     'posts_per_page' => -1,
//     'post_status'    => 'publish',
//     's'              => $query,
//     'orderby'        => 'title',
//     'order'          => 'ASC',
//   ];

//   $tools_query = new WP_Query($args);

//   ob_start();

//   if ($tools_query->have_posts()) {
//     while ($tools_query->have_posts()) {
//       $tools_query->the_post();
//       $title = esc_html(get_the_title());
//       $id = get_the_ID();

//       echo "<li class=\"wcl-compare__dropdown-item\" data-id=\"$id\">$title</li>";
//     }
//     wp_reset_postdata();
//   } else {
//     echo '<li class="wcl-compare__dropdown-item disabled">No tools found</li>';
//   }

//   $ajax_response['success'] = 1;
//   $ajax_response['html'] = ob_get_clean();

//   wp_send_json($ajax_response);
// }

// add_action('wp_ajax_autocomplete_first_tool', 'autocomplete_first_tool');
// add_action('wp_ajax_nopriv_autocomplete_first_tool', 'autocomplete_first_tool');


// function autocomplete_last_tool()
// {
//   $ajax_response = [
//     'success' => 0,
//     'message' => '',
//     'html'    => '',
//   ];

//   if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
//     $ajax_response['message'] = 'Nonce is missing or invalid';
//     wp_send_json($ajax_response);
//   }

//   $query = trim($_POST['query'] ?? '');
//   $id_first = intval($_POST['id_first'] ?? 0);

//   if (empty($query) || $id_first === 0) {
//     $ajax_response['message'] = 'Missing query or first tool ID';
//     wp_send_json($ajax_response);
//   }

//   $category_ids = wp_get_post_terms($id_first, 'ai_tool_category', ['fields' => 'ids']);
//   if (empty($category_ids)) {
//     $ajax_response['message'] = 'First tool has no categories';
//     wp_send_json($ajax_response);
//   }

//   $args = [
//     'post_type'      => 'ai-tool',
//     'posts_per_page' => -1,
//     'post_status'    => 'publish',
//     's'              => $query,
//     'orderby'        => 'title',
//     'order'          => 'ASC',
//     'post__not_in'   => [$id_first],
//     'tax_query' => [
//       [
//         'taxonomy' => 'ai_tool_category',
//         'field'    => 'term_id',
//         'terms'    => $category_ids,
//         'operator' => 'IN',
//         'include_children' => false,
//       ]
//     ],
//   ];

//   $tools_query = new WP_Query($args);

//   ob_start();

//   if ($tools_query->have_posts()) {
//     while ($tools_query->have_posts()) {
//       $tools_query->the_post();
//       $title = esc_html(get_the_title());
//       $id = get_the_ID();

//       echo "<li class=\"wcl-compare__dropdown-item\" data-id=\"$id\">$title</li>";
//     }
//     wp_reset_postdata();
//   } else {
//     echo '<li class="wcl-compare__dropdown-item disabled">No matching tools found</li>';
//   }

//   $ajax_response['success'] = 1;
//   $ajax_response['html'] = ob_get_clean();

//   wp_send_json($ajax_response);
// }



// add_action('wp_ajax_autocomplete_last_tool', 'autocomplete_last_tool');
// add_action('wp_ajax_nopriv_autocomplete_last_tool', 'autocomplete_last_tool');
function autocomplete_first_tool()
{
  $ajax_response = [
    'success' => 0,
    'message' => '',
    'html'    => '',
  ];


  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
    $ajax_response['message'] = 'Nonce is missing or invalid';
    wp_send_json($ajax_response);
  }

  $query = trim($_POST['query'] ?? '');

  $args = [
    'post_type'      => 'ai-tool',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
  ];

  if (!empty($query) && strlen($query) >= 2) {
    $args['s'] = $query;
  }

  $tools_query = new WP_Query($args);

  ob_start();

  if ($tools_query->have_posts()) {
    while ($tools_query->have_posts()) {
      $tools_query->the_post();
      $title = esc_html(get_the_title());
      $id = get_the_ID();

      echo "<li class=\"wcl-compare__dropdown-item\" data-id=\"$id\">$title</li>";
    }
    wp_reset_postdata();
  } else {
    echo '<li class="wcl-compare__dropdown-item disabled">No tools found</li>';
  }

  $ajax_response['success'] = 1;
  $ajax_response['html'] = ob_get_clean();

  wp_send_json($ajax_response);
}

add_action('wp_ajax_autocomplete_first_tool', 'autocomplete_first_tool');
add_action('wp_ajax_nopriv_autocomplete_first_tool', 'autocomplete_first_tool');

function autocomplete_last_tool()
{
  $ajax_response = [
    'success' => 0,
    'message' => '',
    'html'    => '',
  ];

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
    $ajax_response['message'] = 'Invalid nonce';
    wp_send_json($ajax_response);
  }

  $query = trim($_POST['query'] ?? '');
  $id_first = intval($_POST['id_first'] ?? 0);

  if ($id_first === 0) {
    $ajax_response['message'] = 'Missing first tool ID';
    wp_send_json($ajax_response);
  }

  $category_ids = wp_get_post_terms($id_first, 'ai_tool_category', ['fields' => 'ids']);
  if (empty($category_ids)) {
    $ajax_response['message'] = 'First tool has no categories';
    wp_send_json($ajax_response);
  }

  $args = [
    'post_type'      => 'ai-tool',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
    'post__not_in'   => [$id_first],
    'tax_query' => [
      [
        'taxonomy'         => 'ai_tool_category',
        'field'            => 'term_id',
        'terms'            => $category_ids,
        'operator'         => 'IN',
        'include_children' => false,
      ]
    ],
  ];

  if (!empty($query) && strlen($query) >= 2) {
    $args['s'] = $query;
  }

  $tools_query = new WP_Query($args);

  ob_start();

  if ($tools_query->have_posts()) {
    while ($tools_query->have_posts()) {
      $tools_query->the_post();
      $title = esc_html(get_the_title());
      $id    = get_the_ID();

      echo "<li class=\"wcl-compare__dropdown-item\" data-id=\"$id\">$title</li>";
    }
    wp_reset_postdata();
  } else {
    echo '<li class="wcl-compare__dropdown-item disabled">No matching tools found</li>';
  }

  $ajax_response['success'] = 1;
  $ajax_response['html']    = ob_get_clean();

  wp_send_json($ajax_response);
}

add_action('wp_ajax_autocomplete_last_tool', 'autocomplete_last_tool');
add_action('wp_ajax_nopriv_autocomplete_last_tool', 'autocomplete_last_tool');





function tools_compare()
{
  $ajax_response = [
    'success' => 0,
    'message' => '',
    'html'    => '',
  ];

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
    $ajax_response['message'] = 'Nonce is missing or invalid';
    wp_send_json($ajax_response);
  }

  $id_first = intval($_POST['first_tool_id'] ?? 0);
  $id_last  = intval($_POST['last_tool_id'] ?? 0);

  if (!$id_first || !$id_last) {
    $ajax_response['message'] = 'Invalid tool IDs';
    wp_send_json($ajax_response);
  }

  $tools = get_posts([
    'post_type'      => 'ai-tool',
    'post__in'       => [$id_first, $id_last],
    'posts_per_page' => 2,
    'orderby'        => 'post__in',
  ]);

  if (count($tools) < 2) {
    $ajax_response['message'] = 'One or both tools not found';
    wp_send_json($ajax_response);
  }

  // Get all use case terms for both tools
  $tool_use_cases = [];
  $all_use_case_names = [];

  foreach ($tools as $tool) {
    $terms = get_the_terms($tool, 'ai_tool_use_case');
    $term_slugs = [];

    if ($terms && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        $term_slugs[] = $term->slug;
        $all_use_case_names[$term->slug] = $term->name;
      }
    }

    $tool_use_cases[$tool->ID] = $term_slugs;
  }

  // get unique use cases list
  $all_use_case_slugs = array_keys($all_use_case_names);

  ob_start();
?>
  <div class="row row-cols-1 row-cols-md-2 g-4">
    <?php foreach ($tools as $tool): ?>
      <div class="col">
        <div class="card h-100">
          <?php if (has_post_thumbnail($tool)): ?>
            <img src="<?= get_the_post_thumbnail_url($tool, 'medium') ?>" class="card-img-top" alt="<?= esc_attr($tool->post_title) ?>">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= esc_html($tool->post_title) ?></h5>
            <p class="card-text"><?= wp_trim_words($tool->post_content, 40, '...') ?></p>

            <?php
            $taxonomies = ['ai_tool_category', 'ai_tool_integration'];
            foreach ($taxonomies as $taxonomy) {
              $terms = get_the_terms($tool, $taxonomy);
              if ($terms && !is_wp_error($terms)) {
                echo '<p class="mb-1"><strong>' . esc_html(get_taxonomy($taxonomy)->labels->name) . ':</strong> ';
                echo implode(', ', array_map(function ($term) {
                  return esc_html($term->name);
                }, $terms));
                echo '</p>';
              }
            }
            ?>

            <div class="mt-3">
              <strong>Use Cases:</strong>
              <ul class="list-unstyled mt-2">
                <?php foreach ($all_use_case_slugs as $slug):
                  $has_use_case = in_array($slug, $tool_use_cases[$tool->ID]);
                ?>
                  <li class="<?= $has_use_case ? 'text-success has-use-case' : 'text-danger no-use-case' ?>">
                    <?= $has_use_case ? '✔' : '✖' ?>
                    <?= esc_html($all_use_case_names[$slug]) ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php

  $tool1_slug = get_post_field('post_name', $id_first);
  $tool2_slug = get_post_field('post_name', $id_last);

  $ajax_response['tool1_slug'] = $tool1_slug;
  $ajax_response['tool2_slug'] = $tool2_slug;


  $ajax_response['success'] = 1;
  $ajax_response['html']    = ob_get_clean();
  wp_send_json($ajax_response);
}



add_action('wp_ajax_tools_compare', 'tools_compare');
add_action('wp_ajax_nopriv_tools_compare', 'tools_compare');

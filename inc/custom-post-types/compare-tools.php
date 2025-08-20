<?php

function wcl_register_post_type_compare_tools()
{
  $args = [
    'label' => 'Compare Tools',
    'public' => true,
    'menu_icon' => 'dashicons-hammer',
    'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
    'has_archive' => true,
    'rewrite' => [
      'slug' => 'compare-ai-tools',
      'with_front' => false,
    ],

    'publicly_queryable' => true,
    'show_in_rest' => true,

  ];

  wcl_register_post_type('Compare Tool', 'Compare Tools', $args);
}

add_action('init', 'wcl_register_post_type_compare_tools');

//metabox
add_action('add_meta_boxes', function () {
  add_meta_box(
    'compare_tool_ai_selector',
    'Select AI Tool',
    'wcl_render_ai_tool_select',
    'compare-tool',
    'advanced',
    'high'
  );
});

function wcl_render_ai_tool_select($post)
{
  $selected_tool = get_post_meta($post->ID, '_compare_tool_selected', true);
  $selected_related_tool = get_post_meta($post->ID, '_compare_tool_related_selected', true);

  $ai_tools = get_posts([
    'post_type' => 'ai-tool',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
  ]);

  echo '<div style="display: flex; gap: 20px; align-items: flex-start;">';

  echo '<div style="flex: 1; min-width: 200px;">';
  echo '<label for="ai-tool-main-select" style="display: block; margin-bottom: 6px; font-weight: 600;">Select AI Tool</label>';
  echo '<select id="ai-tool-main-select" name="compare_tool_selected" style="width: 100%;">';
  echo '<option value="">— Select AI Tool —</option>';
  foreach ($ai_tools as $tool) {
    $selected = ($tool->ID == $selected_tool) ? 'selected' : '';
    echo "<option value='{$tool->ID}' $selected>" . esc_html($tool->post_title) . "</option>";
  }
  echo '</select>';
  echo '</div>';

  echo '<div style="flex: 1; min-width: 200px;">';
  echo '<label for="ai-tool-related-select" style="display: block; margin-bottom: 6px; font-weight: 600;">Select Related AI Tool</label>';
  echo '<select id="ai-tool-related-select" name="compare_tool_related_selected" style="width: 100%;">';

  if ($selected_tool) {
    $terms = wp_get_post_terms($selected_tool, 'ai_tool_category', ['fields' => 'ids']);
    if (!is_wp_error($terms) && !empty($terms)) {
      $related_tools = get_posts([
        'post_type' => 'ai-tool',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => [
          [
            'taxonomy' => 'ai_tool_category',
            'field' => 'term_id',
            'terms' => $terms,
            'operator' => 'IN',
          ],
        ],
        'post__not_in' => [$selected_tool],
      ]);

      foreach ($related_tools as $tool) {
        $selected = ($tool->ID == $selected_related_tool) ? 'selected' : '';
        echo "<option value='{$tool->ID}' $selected>" . esc_html($tool->post_title) . "</option>";
      }
    }
  } else {
    echo '<option value="">— Select main AI Tool first —</option>';
  }

  echo '</select>';
  echo '</div>';

  echo '</div>';
}






add_action('save_post_compare-tool', function ($post_id) {

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  if (!current_user_can('edit_post', $post_id)) return;

  if (isset($_POST['compare_tool_selected']) && $_POST['compare_tool_selected'] !== '') {
    update_post_meta($post_id, '_compare_tool_selected', intval($_POST['compare_tool_selected']));
  } else {
    delete_post_meta($post_id, '_compare_tool_selected');
  }

  if (isset($_POST['compare_tool_related_selected']) && $_POST['compare_tool_related_selected'] !== '') {
    update_post_meta($post_id, '_compare_tool_related_selected', intval($_POST['compare_tool_related_selected']));
  } else {
    delete_post_meta($post_id, '_compare_tool_related_selected');
  }
});



//ajax
add_action('wp_ajax_wcl_get_related_tools', function () {
  if (!current_user_can('edit_posts')) {
    wp_send_json_error('Access denied');
  }

  $main_tool_id = intval($_POST['main_tool_id'] ?? 0);
  if (!$main_tool_id) {
    wp_send_json_error('Missing main tool ID');
  }

  $taxonomies = ['ai_tool_category'];
  $tax_queries = [];

  foreach ($taxonomies as $taxonomy) {
    $terms = wp_get_post_terms($main_tool_id, $taxonomy, ['fields' => 'ids']);
    if (!is_wp_error($terms) && !empty($terms)) {
      $tax_queries[] = [
        'taxonomy' => $taxonomy,
        'field'    => 'term_id',
        'terms'    => $terms,
        'operator' => 'IN',
      ];
    }
  }


  if (empty($tax_queries)) {
    wp_send_json_success([]);
  }

  $query_args = [
    'post_type'      => 'ai-tool',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'tax_query'      => [],
    'post__not_in'   => [$main_tool_id],
  ];

  if (count($tax_queries) === 1) {
    $query_args['tax_query'] = $tax_queries;
  } else {
    $query_args['tax_query'] = [
      'relation' => 'OR',
      ...$tax_queries,
    ];
  }

  $related_tools = get_posts($query_args);

  $result = [];
  foreach ($related_tools as $tool) {
    $result[] = [
      'id'    => $tool->ID,
      'title' => $tool->post_title,
    ];
  }

  wp_send_json_success($result);
});

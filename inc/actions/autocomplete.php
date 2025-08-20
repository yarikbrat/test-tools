<?php

function autocomplete_handler()
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
  if (empty($query)) {
    $ajax_response['message'] = 'Query is empty';
    wp_send_json($ajax_response);
  }

  ob_start();
  echo '<ul class="wcl-hero__autocomplete-list">';

  $categories = get_terms([
    'taxonomy'   => 'ai_tool_category',
    'hide_empty' => false,
    'name__like' => $query,
  ]);

  if (!is_wp_error($categories) && !empty($categories)) {
    echo '<li class="wcl-hero__autocomplete-label">Categories</li>';
    foreach ($categories as $term) {
      $link = esc_url(get_term_link($term));
      $name = esc_html($term->name);
      $count = intval($term->count);
      echo "<li class=\"wcl-hero__autocomplete-item\"><a href=\"$link\" target=\"_blank\">$name ($count)</a></li>";
    }
  }



  $tools = get_posts([
    'post_type'      => 'ai-tool',
    'posts_per_page' => -1,
    's'              => $query,
  ]);

  if (!empty($tools)) {
    echo '<li class="wcl-hero__autocomplete-label">Tools</li>';
    foreach ($tools as $post) {
      $title = esc_html(get_the_title($post));
      $link  = esc_url(get_permalink($post));
      echo '<li class="wcl-hero__autocomplete-item"><a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer">' . esc_html($title) . '</a></li>';
    }
  }

  echo '</ul>';
  $html = ob_get_clean();

  $ajax_response['success'] = 1;
  $ajax_response['html']    = $html;

  wp_send_json($ajax_response);
}
add_action('wp_ajax_autocomplete', 'autocomplete_handler');
add_action('wp_ajax_nopriv_autocomplete', 'autocomplete_handler');

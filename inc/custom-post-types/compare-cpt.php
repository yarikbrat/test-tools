<?php

function wcl_register_post_type_compare()
{
  $args = [
    'label' => 'Compare',
    'public' => true,
    'menu_icon' => 'dashicons-hammer',
    'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
    'has_archive' => true,
    'rewrite' => [
      'slug' => 'compare-tools',
      'with_front' => false,
    ],

    'publicly_queryable' => true,
    'show_in_rest' => true,

  ];

  wcl_register_post_type('Compare', 'Compares', $args);
}

add_action('init', 'wcl_register_post_type_compare');

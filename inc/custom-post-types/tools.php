<?php

function wcl_register_post_type_ai_tool()
{
  $args = [
    'menu_icon' => 'dashicons-hammer',
    'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
    'taxonomies' => ['ai_tool_category'],
  ];

  wcl_register_post_type('AI Tool', 'AI Tools', $args);
}

add_action('init', 'wcl_register_post_type_ai_tool');


/** Custom taxonomy for AI Tools */
function wcl_register_category_ai_tool()
{
  wcl_register_taxonomy(
    'ai_tool_category',
    ['ai-tool'],
    'Category',
    'Categories',
    [
      'public' => true,
      'rewrite' => [
        'slug' => 'ai-tool-category',
        'with_front' => false,
      ],
      'hierarchical' => true,
    ]
  );
}
add_action('init', 'wcl_register_category_ai_tool');

function wcl_register_integrations_ai_tool()
{
  wcl_register_taxonomy(
    'ai_tool_integration',
    ['ai-tool'],
    'Integration',
    'Integrations',
    [
      'public' => true,
      'rewrite' => [
        'slug' => 'ai-tool-integration',
        'with_front' => false,
      ],
      'hierarchical' => true,
    ]
  );
}
add_action('init', 'wcl_register_integrations_ai_tool');

function wcl_register_use_case_ai_tool()
{
  wcl_register_taxonomy(
    'ai_tool_use_case',
    ['ai-tool'],
    'Use Case',
    'Use Cases',
    [
      'public' => true,
      'rewrite' => [
        'slug' => 'ai-tool-use-case',
        'with_front' => false,
      ],
      'hierarchical' => true,
    ]
  );
}
add_action('init', 'wcl_register_use_case_ai_tool');

<?php
// Функция-обёртка для вывода ACF-полей
function print_field($name, $pre = '', $after = '', $default = '', $return = false)
{
  $field = get_field($name);
  $out = $default;
  if (!empty($field)) {
    $out = $pre . $field . $after;
  }
  if ($return) {
    return $out;
  } else {
    echo $out;
  }
}

// Регистрируем кастомную категорию блоков
function example_block_category($categories, $post)
{
  $new_category = array(
    'slug'  => 'sh_templates',
    'title' => 'Test Blocks',
  );

  array_unshift($categories, $new_category); // добавляем в начало массива

  return $categories;
}
add_filter('block_categories', 'example_block_category', 10, 2);


// Регистрируем блок
add_action('acf/init', function () {

  if (!function_exists('acf_register_block')) return;

  $blocks_path     = get_theme_file_path('template-parts'); // путь к шаблонам
  $stylesheet_path = get_stylesheet_directory_uri() . '/template-parts'; // URI для CSS/JS

  acf_register_block(array(
    'name'            => 'hello_world',
    'title'           => __('Hello World', 'your-textdomain'),
    'description'     => __('A simple test block', 'your-textdomain'),
    'render_template' => $blocks_path . '/blocks/hello_world/hello_world.php',
    'category'        => 'sh_templates',
    'icon'            => 'smiley',
    'keywords'        => array('hello', 'test'),
    'mode'            => 'auto',
    'align'           => 'full',
    'supports'        => array('anchor' => true),
    'example'         => array(
      'attributes' => array(
        'mode' => 'auto',
        'data' => array(
          'preview_text' => 'Hello World Block Preview',
          'preview_image' => get_stylesheet_directory_uri() . '/template-parts/blocks/hello_world/preview.png'
        )
      )
    ),
    'enqueue_assets'  => function () use ($stylesheet_path, $blocks_path) {
      wp_enqueue_style('hello-world-block-style', $stylesheet_path . '/blocks/hello_world/hello_world_style.min.css', [], '1.0.0');
      wp_enqueue_script('hello-world-block-script', $stylesheet_path . '/blocks/hello_world/hello_world_script.js', ['jquery'], '1.0.0', true);
    }
  ));
});

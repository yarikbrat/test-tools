<?php

// AJAX обработчик
add_action('wp_ajax_wcl_get_ai_tools', 'wcl_get_ai_tools');
add_action('wp_ajax_nopriv_wcl_get_ai_tools', 'wcl_get_ai_tools');

function wcl_get_ai_tools()
{
  check_ajax_referer('wcl_nonce', 'nonce');

  $cat_param = isset($_POST['cat']) ? sanitize_text_field($_POST['cat']) : '';
  $paged     = isset($_POST['pg']) ? (int) $_POST['pg'] : 1;

  $args = [
    'post_type'      => 'ai-tool',
    'posts_per_page' => 3,
    'paged'          => $paged,
  ];

  if ($cat_param) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'ai_tool_category',
        'field'    => 'slug',
        'terms'    => $cat_param,
      ],
    ];
  }

  $query = new WP_Query($args);

  ob_start();
  if ($query->have_posts()):
    while ($query->have_posts()): $query->the_post(); ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <?php if (has_post_thumbnail()): ?>
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
            </a>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h5>
            <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
          </div>
        </div>
      </div>
<?php endwhile;
  endif;
  $html = ob_get_clean();

  wp_send_json([
    'success'  => true,
    'html'     => $html,
    'has_more' => ($query->max_num_pages > $paged),
  ]);
}

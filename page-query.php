<?php
$posts_per_page = 3;
$paged = isset($_GET['pg']) ? max(1, (int) $_GET['pg']) : 1;
$cat_param = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';

// считаем сколько постов нужно отобразить
$posts_to_show = $posts_per_page * $paged;

$args = [
  'post_type'      => 'ai-tool',
  'posts_per_page' => $posts_to_show,
  'orderby'        => 'date',
  'order'          => 'DESC',
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

$remaining_posts = $query->found_posts - $posts_to_show;

// показываем кнопку Load More, если остались хотя бы 1 пост
$show_load_more = $remaining_posts > 0;

get_header();
?>

<div class="container py-5">

  <!-- Навигация по категориям -->
  <?php
  $parent_terms = get_terms([
    'taxonomy'   => 'ai_tool_category',
    'parent'     => 0,
    'hide_empty' => false,
  ]);
  ?>
  <nav class="nav mb-4">
    <?php foreach ($parent_terms as $parent): ?>
      <?php
      $child_terms = get_terms([
        'taxonomy'   => 'ai_tool_category',
        'parent'     => $parent->term_id,
        'hide_empty' => false,
      ]);
      ?>
      <div class="nav-item dropdown me-3">
        <a href="?cat=<?php echo esc_attr($parent->slug); ?>"
          class="nav-link dropdown-toggle <?php echo ($cat_param === $parent->slug) ? 'active' : ''; ?>"
          id="cat-<?php echo $parent->term_id; ?>"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          data-cat="<?php echo esc_attr($parent->slug); ?>">
          <?php echo esc_html($parent->name); ?>
        </a>

        <?php if (!empty($child_terms)): ?>
          <ul class="dropdown-menu" aria-labelledby="cat-<?php echo $parent->term_id; ?>">
            <li>
              <a class="dropdown-item fw-bold <?php echo ($cat_param === $parent->slug) ? 'active' : ''; ?>"
                href="?cat=<?php echo esc_attr($parent->slug); ?>"
                data-cat="<?php echo esc_attr($parent->slug); ?>">
                <?php echo esc_html($parent->name); ?>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <?php foreach ($child_terms as $child): ?>
              <li>
                <a class="dropdown-item <?php echo ($cat_param === $child->slug) ? 'active' : ''; ?>"
                  href="?cat=<?php echo esc_attr($child->slug); ?>"
                  data-cat="<?php echo esc_attr($child->slug); ?>">
                  <?php echo esc_html($child->name); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </nav>


  <!-- Контейнер для постов -->
  <div class="row g-4" id="wcl-posts-container">
    <?php if ($query->have_posts()): ?>
      <?php while ($query->have_posts()): $query->the_post(); ?>
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
      <?php endwhile; ?>
    <?php else: ?>
      <p>No posts found</p>
    <?php endif; ?>
  </div>

  <!-- Кнопка Load More -->
  <?php if ($show_load_more): ?>
    <div class="text-center mt-4">
      <a href="#"
        style="display: block;"
        class="btn btn-primary"
        id="load-more"
        data-cat="<?php echo esc_attr($cat_param); ?>"
        data-pg="<?php echo $paged; ?>">
        Load More
      </a>
    </div>
  <?php endif; ?>

</div>

<?php
wp_reset_postdata();
get_footer();

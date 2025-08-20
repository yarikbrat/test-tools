<?php

/**
 * Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

// Example of getting a field from ACF
// $title = get_field( 'content' )[ 'left' ][ 'title' ];


// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) {
  $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

$tool_level = get_terms([
  'taxonomy' => 'ai_tool_category',
  'hide_empty' => false,
  'parent' => 0,
])


?>


<div class="row">
  <div class="col-3">
    <div class="wcl-container">
      <h3 class="text-center">Filters</h3>
      <div class="cat-container">
        <form id="filter">
          <?php wp_nonce_field('test_tools', 'test_tools_wpnonce'); ?>


          <!-- Integration -->
          <?php
          $integration = get_terms([
            'taxonomy' => 'ai_tool_integration',
            'hide_empty' => false,
            'parent' => 0,
          ]);
          if (!is_wp_error($integration)): ?>
            <div class="integration-wrapper mb-3">
              <h4>Integration</h4>
              <?php foreach ($integration as $term): ?>
                <div class="integration">
                  <input type="checkbox" name="integration[]" value="<?php echo $term->term_id; ?>" id="integration-<?php echo $term->term_id; ?>">
                  <label for="integration-<?php echo $term->term_id; ?>"><?php echo $term->name; ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- Use Case -->
          <?php
          $use_cases = get_terms([
            'taxonomy' => 'ai_tool_use_case',
            'hide_empty' => false,
            'parent' => 0,
          ]);
          if (!is_wp_error($use_cases)): ?>
            <div class="integration-wrapper">
              <h4>Use Case</h4>
              <?php foreach ($use_cases as $term): ?>
                <div class="integration">
                  <input type="checkbox" name="use_case[]" value="<?php echo $term->term_id; ?>" id="use_case-<?php echo $term->term_id; ?>">
                  <label for="use_case-<?php echo $term->term_id; ?>"><?php echo $term->name; ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>


          <button type="submit">Filter</button>
        </form>

      </div>
    </div>

  </div>
  <div class="col-9">
    <section <?= $anchor; ?>class="wcl-tools-new">
      <div class="wcl-container">
        <?php if (!empty($tool_level) && !is_wp_error($tool_level)): ?>
          <?php foreach ($tool_level as $term): ?>

            <div class="container text-center mb-3">
              <div class="row mb-3">
                <h2>

                  <?php echo $term->name; ?>


                </h2>
                <?php
                $child_terms = get_terms([
                  'taxonomy' => 'ai_tool_category',
                  'hide_empty' => false,
                  'parent' => $term->term_id,
                ]);

                // wcl_dump($child_terms);

                if (!empty($child_terms)): ?>
                  <div class="col category-term mb-3">
                    <span data-id="all" data-parent-id="<?php echo $child_terms['0']->parent ?>" class="w-100 h-100 cat-span">All</span>
                  </div>
                  <?php foreach ($child_terms as $child_term): ?>
                    <div class="col category-term">
                      <span data-id="<?php echo $child_term->term_id; ?>" class="w-100 h-100 cat-span"><?php echo $child_term->name; ?></span>
                    </div>
                  <?php endforeach; ?>
                  <div class="col category-term">
                    <span data-id="more" class="w-100 h-100 cat-span">More</span>
                  </div>
                <?php endif; ?>

              </div>
              <div class="row g-4" data-term-id="<?php echo $term->term_id ?>">
                <?php
                $posts_in_term = new WP_Query([
                  'post_type' => 'ai-tool',
                  'tax_query' => [
                    [
                      'taxonomy' => 'ai_tool_category',
                      'field' => 'term_id',
                      'terms' => $term->term_id,
                    ],
                  ],
                  'posts_per_page' => -1,
                ]);
                if ($posts_in_term->have_posts()):
                  while ($posts_in_term->have_posts()): $posts_in_term->the_post(); ?>

                    <div class="col-12 col-sm-6 col-lg-4 d-flex">
                      <div class="card w-100 h-100 shadow-lg">



                        <?php if (has_post_thumbnail()): ?>
                          <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php else: ?>
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/no-image.webp" class="card-img-top" alt="Default thumbnail">
                        <?php endif; ?>

                        <?php
                        $post_terms = get_the_terms(get_the_ID(), 'ai_tool_category');
                        if (!empty($post_terms) && !is_wp_error($post_terms)) : ?>
                          <div class="card-body pt-3 pb-0">
                            <div class="d-flex flex-wrap gap-2 mb-2">
                              <?php foreach ($post_terms as $post_term): ?>
                                <a href="<?php echo esc_url(get_term_link($post_term)); ?>" class="badge bg-primary text-decoration-none ">
                                  <?php echo esc_html($post_term->name); ?>
                                </a>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        <?php endif; ?>

                        <div class="card-body">
                          <a href="<?php echo the_permalink(); ?>" class="text-decoration-none text-dark w-100 h-100">
                            <h5 class="card-title mb-2">

                              <?php the_title(); ?>

                            </h5>
                            <?php
                            $excerpt = get_the_excerpt();
                            ?>
                            <?php if (!empty($excerpt)): ?>
                              <p class="card-text">

                                <?php echo $excerpt; ?>

                              </p>
                            <?php endif; ?>
                          </a>
                        </div>

                      </div>
                    </div>

                  <?php endwhile;
                  wp_reset_postdata();
                else: ?>
                  <p><?php esc_html_e('No AI Tools found in this category.'); ?></p>
                <?php endif; ?>
              </div>

            </div>

          <?php endforeach; ?>

        <?php endif; ?>
      </div>
    </section>
  </div>
</div>
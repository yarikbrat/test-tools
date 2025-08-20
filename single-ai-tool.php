<?php get_header(); ?>

<?php
$orig_post_id = get_queried_object_id();
$orig_post = get_post($orig_post_id);
$orig_post_slug = $orig_post->post_name;
$orig_post_title = get_the_title($orig_post_id);
?>


<main id="wcl-page-content" class="wcl-page-content">
  <div class="wcl-container">
    <div class="row mt-3">
      <div class="col-3 taxonomy-links">
        <h2>Recent Categories</h2>
        <?php
        $terms = get_the_terms(get_the_ID(), 'ai_tool_category');
        if ($terms && !is_wp_error($terms)): ?>
          <div class="wcl-terms">
            <?php foreach ($terms as $term): ?>
              <div class="wcl-term">
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="text-decoration-none">
                  <?php echo esc_html($term->name); ?>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-9">
        <?php if (has_post_thumbnail()): ?>
          <div class="wcl-futured-image text-center">
            <?php
            $thumbnail_id = get_post_thumbnail_id();
            echo wp_get_attachment_image($thumbnail_id, 'full', false, [
              'class' => 'img-fluid',
              'alt' => get_the_title(),
            ]);
            ?>
          </div>
        <?php endif; ?>
        <?php
        $title = get_the_title();
        if ($title != ''): ?>
          <h1 class="text-center"><?php echo $title; ?></h1>
          <?php the_content(); ?>
        <?php endif; ?>

        <?php
        $terms = get_the_terms(get_the_ID(), 'ai_tool_category');
        $term_ids = [];

        if ($terms && !is_wp_error($terms)):
          $term_ids = wp_list_pluck($terms, 'term_id');

          $raw_query = new WP_Query([
            'post_type'      => 'ai-tool',
            'posts_per_page' => 20,
            'post__not_in'   => [get_the_ID()],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [[
              'taxonomy' => 'ai_tool_category',
              'field'    => 'term_id',
              'terms'    => $term_ids,
              'operator' => 'IN',
              'include_children' => false,
            ]],
          ]);

          $relevant_posts = [];

          if ($raw_query->have_posts()):
            while ($raw_query->have_posts()): $raw_query->the_post();
              $matched_terms = get_the_terms(get_the_ID(), 'ai_tool_category');
              if ($matched_terms && !is_wp_error($matched_terms)) {
                $matched_ids = wp_list_pluck($matched_terms, 'term_id');
                $common_terms = array_intersect($term_ids, $matched_ids);
                $relevant_posts[] = [
                  'post'        => get_post(),
                  'match_count' => count($common_terms),
                ];
              }
            endwhile;
            wp_reset_postdata();

            usort($relevant_posts, function ($a, $b) {
              return $b['match_count'] - $a['match_count'];
            });

            $top_posts = array_slice($relevant_posts, 0, 3);

        ?>

            <?php if (!empty($top_posts)): ?>
              <div class="wcl-related-tools mt-5">
                <h2 class="text-center">Related Tools</h2>
                <div class="row mb-2">
                  <?php foreach ($top_posts as $item):
                    $post = $item['post'];
                    setup_postdata($post); ?>
                    <div class="col-12 col-sm-6 col-lg-4 d-flex">
                      <div class="card w-100 h-100 shadow-lg">
                        <?php if (has_post_thumbnail($post)): ?>
                          <img src="<?php echo get_the_post_thumbnail_url($post); ?>" class="card-img-top" alt="<?php echo esc_attr(get_the_title($post)); ?>">
                        <?php else: ?>
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/no-image.webp" class="card-img-top" alt="Default thumbnail">
                        <?php endif; ?>

                        <?php
                        $post_terms = get_the_terms(get_the_ID(), 'ai_tool_category');
                        if (!empty($post_terms) && !is_wp_error($post_terms)): ?>
                          <div class="card-body pt-3 pb-0">
                            <div class="d-flex flex-wrap gap-2 mb-2">
                              <?php foreach ($post_terms as $post_term): ?>
                                <a href="<?php echo esc_url(get_term_link($post_term)); ?>" class="badge bg-primary text-decoration-none">
                                  <?php echo esc_html($post_term->name); ?>
                                </a>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        <?php endif; ?>

                        <div class="card-body">
                          <a href="<?php echo get_permalink($post); ?>" class="text-decoration-none text-dark w-100 h-100">
                            <h5 class="card-title text-center"><?php echo get_the_title($post); ?></h5>
                            <?php if ($excerpt = get_the_excerpt($post)): ?>
                              <p class="card-text text-center"><?php echo $excerpt; ?></p>
                            <?php endif; ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php wp_reset_postdata(); ?>
              </div>
            <?php endif; ?>


            <div class="row">
              <?php $slug = get_post_field('post_name', get_the_ID()); ?>
              <a class="text-decoration-none text-dark text-center" href="<?php echo esc_url(home_url('/alternatives/' . $orig_post_slug)); ?>">
                <?php echo esc_html($title) . ' Alternatives'; ?>
              </a>
            </div>
      </div>

  <?php
            wp_reset_postdata();
          else:
            echo '<p class="text-center">No relevant tools found.</p>';
          endif;
        endif;

  ?>

    </div>
  </div>
  </div>
</main>

<?php get_footer(); ?>
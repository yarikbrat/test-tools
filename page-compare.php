<?php get_header(); ?>

<?php
$tool1 = get_query_var('tool1');
$tool2 = get_query_var('tool2');


?>



<section class="wcl-compare"
  data-tool1="<?= esc_attr($tool1); ?>"
  data-tool2="<?= esc_attr($tool2); ?>">
  <div class="wcl-container">
    <div class="row">
      <div class="col-6 mb-3">
        <div class="wcl-compare__tool-first-wrapper w-100">
          <input type="text" class="wcl-input w-100 form-control" id="first-tool" data-id="" placeholder="Search for a tool to compare">
          <ul class="wcl-compare__drop-wrapper list-group position-absolute w-100 zindex-dropdown mt-1" id="first-tool-drop-wrapper"></ul>

        </div>
      </div>
      <div class="col-6 mb-3">
        <div class="wcl-compare__tool-last-wrapper w-100">
          <input type="text" class="wcl-input w-100 form-control" id="last-tool" placeholder="Search for a tool to compare" disabled="true" data-id="">
          <ul class="wcl-compare__drop-wrapper list-group position-absolute w-100 zindex-dropdown mt-1" id="last-tool-drop-wrapper"></ul>
        </div>
      </div>
      <div class="col-12">
        <button id="compare-btn" class="btn btn-outline-primary w-100" disabled="true">Compare</button>
      </div>
    </div>
    <div id="compare-wrapper" class="wcl-compare__tools-wrapper">
      <?php
      if ($tool1 && $tool2):

        $tools = get_posts([
          'post_type'        => 'ai-tool',
          'post_name__in'    => [$tool1, $tool2],
          'posts_per_page'   => 2,
          'orderby'          => 'post_name__in',
        ]);

        if (count($tools) === 2):

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

          $all_use_case_slugs = array_keys($all_use_case_names);
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
                    foreach ($taxonomies as $taxonomy):
                      $terms = get_the_terms($tool, $taxonomy);
                      if ($terms && !is_wp_error($terms)):
                    ?>
                        <p class="mb-1"><strong><?= esc_html(get_taxonomy($taxonomy)->labels->name); ?>:</strong>
                          <?= implode(', ', array_map(fn($term) => esc_html($term->name), $terms)); ?>
                        </p>
                    <?php
                      endif;
                    endforeach;
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
        else:
          echo '<div class="alert alert-warning">One or both tools not found.</div>';
        endif;

      endif;
      ?>
    </div>

  </div>
</section>
<?php the_content(); ?>
<?php get_footer(); ?>
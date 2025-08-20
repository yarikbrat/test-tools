<?php get_header(); ?>

<?php
$tool_1_id = get_post_meta(get_the_ID(), '_compare_tool_selected', true);
$tool_2_id = get_post_meta(get_the_ID(), '_compare_tool_related_selected', true);

$tool_1 = get_post($tool_1_id);
$tool_2 = get_post($tool_2_id);
$tools = [$tool_1, $tool_2];
$conclusion = get_field('conclution');

// Use Cases
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

<section class="wcl-compare py-5">
  <div class="container">
    <div class="card shadow-sm mb-5">
      <div class="card-body">
        <!-- Titles & Thumbnails -->
        <div class="row text-center mb-4">
          <?php foreach ($tools as $tool): ?>
            <div class="col-md-6">
              <h4><?= esc_html($tool->post_title) ?></h4>
              <?php if (has_post_thumbnail($tool)): ?>
                <img src="<?= get_the_post_thumbnail_url($tool, 'medium') ?>" class="img-fluid mb-2" alt="<?= esc_attr($tool->post_title) ?>">
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Description -->
        <div class="row mb-4">
          <?php foreach ($tools as $tool): ?>
            <div class="col-md-6">
              <h5>Description</h5>
              <p><?= wp_trim_words($tool->post_content, 40, '...') ?></p>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Taxonomies -->
        <?php
        $taxonomies = ['ai_tool_category', 'ai_tool_integration'];
        foreach ($taxonomies as $taxonomy):
        ?>
          <div class="row mb-4">
            <?php foreach ($tools as $tool): ?>
              <div class="col-md-6">
                <h5><?= esc_html(get_taxonomy($taxonomy)->labels->name) ?></h5>
                <?php
                $terms = get_the_terms($tool, $taxonomy);
                if ($terms && !is_wp_error($terms)) {
                  echo implode(', ', array_map(function ($term) {
                    return esc_html($term->name);
                  }, $terms));
                } else {
                  echo '—';
                }
                ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>

        <!-- Use Cases Table -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="text-center mb-3">Use Cases Comparison</h5>
            <table class="table table-bordered text-center">
              <thead class="table-light">
                <tr>
                  <th><?= esc_html($tool_1->post_title) ?></th>
                  <th>Use Case</th>
                  <th><?= esc_html($tool_2->post_title) ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all_use_case_slugs as $slug): ?>
                  <tr>
                    <td class="<?= in_array($slug, $tool_use_cases[$tool_1->ID]) ? 'text-success' : 'text-danger' ?>">
                      <?= in_array($slug, $tool_use_cases[$tool_1->ID]) ? '✔' : '✖' ?>
                    </td>
                    <td><?= esc_html($all_use_case_names[$slug]) ?></td>
                    <td class="<?= in_array($slug, $tool_use_cases[$tool_2->ID]) ? 'text-success' : 'text-danger' ?>">
                      <?= in_array($slug, $tool_use_cases[$tool_2->ID]) ? '✔' : '✖' ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Conclusion -->
        <?php if ($conclusion): ?>
          <div class="row mt-5">
            <div class="col-12">
              <div class="card border-success">
                <div class="card-header bg-success text-white">
                  <strong>Conclusion</strong>
                </div>
                <div class="card-body">
                  <p class="card-text mb-0"><?= wp_kses_post($conclusion) ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
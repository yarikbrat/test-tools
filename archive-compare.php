<?php get_header(); ?>

<div class="wcl-compare-block container mb-5 pb-5">
  <h1 class="text-center my-4"><?php post_type_archive_title(); ?></h1>

  <?php if (have_posts()) : ?>
    <div class="row g-3">
      <?php while (have_posts()) : the_post();

        $tool_1 = get_field('tool_1');
        $tool_2 = get_field('tool_2');

        if (!$tool_1 || !$tool_2) continue;

        $img1 = get_the_post_thumbnail_url($tool_1->ID, 'medium');
        $img2 = get_the_post_thumbnail_url($tool_2->ID, 'medium');
      ?>
        <div class="col-md-3">
          <a href="<?php the_permalink(); ?>" class="card h-100 text-decoration-none">
            <div class="card-img-top d-flex p-2 gap-0">
              <?php if ($img1): ?>
                <img src="<?php echo esc_url($img1); ?>" alt="" class="img-fluid w-50">
              <?php endif; ?>
              <?php if ($img2): ?>
                <img src="<?php echo esc_url($img2); ?>" alt="" class="img-fluid w-50">
              <?php endif; ?>
            </div>
            <div class="card-body text-center">
              <h5 class="card-title mb-0"><?php the_title(); ?></h5>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="mt-4 text-center">
      <?php
      the_posts_pagination([
        'mid_size'  => 2,
        'prev_text' => '« Previous',
        'next_text' => 'Next »',
        'class'     => 'justify-content-center',
      ]);
      ?>
    </div>

  <?php else : ?>
    <p class="text-center">No compares found.</p>
  <?php endif; ?>

</div>

<?php get_footer(); ?>
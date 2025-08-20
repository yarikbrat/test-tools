<?php get_header(); ?>

<section class="wcl-archive">
  <div class="wcl-container">
    <h1 class="archive-title">
      <?php single_term_title(); ?>
    </h1>

    <?php if (term_description()) : ?>
      <div class="archive-description">
        <?php echo term_description(); ?>
      </div>
    <?php endif; ?>

    <div class="wcl-posts">
      <?php if (have_posts()) : ?>
        <div class="post-list">
          <?php while (have_posts()) : the_post(); ?>
            <article class="post-item">
              <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <?php the_excerpt(); ?>
            </article>
          <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(); ?>

      <?php else : ?>
        <p>Нет записей в этой категории.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
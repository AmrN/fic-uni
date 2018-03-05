<?php 
  get_header();
  while(have_posts()) {
    the_post();
    pageBanner();
?>
  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('program') ?>">
          <i class="fa fa-home" aria-hidden="true"></i>All Programs
        </a>
         <span class="metabox__main"><?php the_title(); ?></span>
      </p>
    </div>
    <div class="generic-content">
      <?php the_field('main_body_content'); ?>
    </div>

    <!-- Professors -->
    <?php
      $relatedProfessors = new WP_Query([
        'post_type' => 'professor',
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => [
          [
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID() . '"',
          ]
        ],
        'posts_per_page' => -1,
      ]);
    ?>
    <?php if ($relatedProfessors->have_posts()): ?>
      <hr class="section-break">
      <h2 class="headline headline--medium"><?php echo get_the_title() ?>Professors</h2>
      <ul class="professor-cards">
        <?php
          while($relatedProfessors->have_posts()) {
            $relatedProfessors->the_post();
        ?>
          <li class="professor-card__list-item">
            <a class="professor-card" href="<?php the_permalink() ?>">
              <img class="professor-card__image" src="<?php echo the_post_thumbnail_url('professorLandscape') ?>" alt="<?php the_title(); ?>">
              <span class="professor-card__name"><?php the_title(); ?></span>
            </a>
          </li>
        <?php
          }
          wp_reset_postdata();
        ?>
      </ul>
    <?php endif; ?>


    <!-- Related Events -->
    <?php
      $today = date('Ymd');
      $homePageEvents = new WP_Query([
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => [
          [
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric',
          ],
          [
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID() . '"',
          ]
        ],
        'posts_per_page' => 2,
      ]);
    ?>
    <?php if ($homePageEvents->have_posts()): ?>
      <hr class="section-break">
      <h2 class="headline headline--medium">Upcoming <?php echo get_the_title() ?> Events</h2>
      <?php
        while($homePageEvents->have_posts()) {
          $homePageEvents->the_post();
          get_template_part('./template-parts/content-event');
        }
        wp_reset_postdata();
      ?>
    <?php endif; ?>


    <!-- Related Campuses -->
    <?php 
      $relatedCampuses = get_field('related_campus');
      if ($relatedCampuses):
    ?>
      <hr class="section-break">
      <h2 class="headline headline--medium"><?php the_title() ?> is available at these Campuses</h2>
      <ul class="min-list link-list">
        <?php foreach ($relatedCampuses as $campus): ?>
          <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus) ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

<?php
  }
  get_footer();
?>  
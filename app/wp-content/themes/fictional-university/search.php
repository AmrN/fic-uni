<?php
  get_header();
  pageBanner([
    'title' => 'Search Results',
    'subtitle' => "You searched for &ldquo;" . esc_html(get_search_query(false)) . "&rdquo;" ,
  ]);
?>
  <div class="container container--narrow page-section">
    <?php if (have_posts()): ?>
      <?php
        while(have_posts()) {
          the_post();
          get_template_part('template-parts/content', get_post_type());
        }
        echo paginate_links();
      ?>
    <?php else: ?>
        <h2 class="headline headline--small-plus">No results match that search</h2>
    <?php endif; ?>
    <?php get_search_form(); ?>
  </div>
<?php
  get_footer();
?>
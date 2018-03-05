<?php 
  get_header();
  while(have_posts()) {
    the_post();
    pageBanner();
?>

  <div class="container container--narrow page-section">
    <?php 
      $parent_ID = wp_get_post_parent_id(get_the_ID());
      if ($parent_ID != 0) {
    ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?= get_permalink($parent_ID) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($parent_ID) ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
    <?php
      }
    ?>
    
    <?php 
      $testArray = get_pages([
        'child_of' => get_the_ID(),
      ]);
      if ($parent_ID or $testArray):
    ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?= get_permalink($parent_ID) ?>"><?= get_the_title($parent_ID) ?></a></h2>
        <ul class="min-list">
          <?php
            if ($parent_ID != 0) {
              $findChildrenOf = $parent_ID;
            } else {
              $findChildrenOf = get_the_ID();
            }
            wp_list_pages([
              'title_li' => NULL,
              'child_of' => $findChildrenOf,
            ]);
          ?>
          <!-- <li class="current_page_item"><a href="#">Our History</a></li>
          <li><a href="#">Our Goals</a></li> -->
        </ul>
      </div>
    <?php endif; ?>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>

<?php
  }
  get_footer();
?>  
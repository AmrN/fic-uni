<?php
  require_once(get_theme_file_path('/includes/search-route.php'));
  require_once(get_theme_file_path('/includes/like-route.php'));
  
  function pp($toPrint) {
    echo '<pre>', print_r($toPrint),'</pre>';
  }

  function pageBanner($args=[]) {
    $title = $args['title'] ?? get_the_title();
    $subtitle = $args['subtitle'] ?? get_field('page_banner_subtitle');
    $photo = $args['photo'] ?? get_field('page_banner_background')['sizes']['pageBanner'] ?? get_theme_file_uri('/images/ocean.jpg');
  ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $photo; ?>);"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $title ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $subtitle; ?></p>
        </div>
      </div>  
    </div>
  <?php
  }

  function university_files() {
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCCD6hT4ErtbLpmB8sjDIFnnBi38WZ8QWs', NULL, '1.0', true);
    wp_enqueue_script('university_main_javascript', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    wp_enqueue_style('google-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style( 'university_main_styles', get_stylesheet_uri());

    // store global js data
    wp_localize_script( 'university_main_javascript', 'university_data', [
      'root_url' => site_url(),
      'nonce' => wp_create_nonce('wp_rest'),
    ]);

  }

  function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
  }

  function university_adjust_queries($query) {
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
      $today = date('Ymd');
      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', [
        [
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric',
        ]
      ]);
    }

    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', -1);
    }

    if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
      $query->set('posts_per_page', -1);
    }
  }

  function university_map_key($api) {
    $api['key'] = 'AIzaSyCCD6hT4ErtbLpmB8sjDIFnnBi38WZ8QWs';
    return $api;
  }

  function university_custom_rest() {
    register_rest_field('post', 'authorName', [
      'get_callback' => function($post) {return get_the_author();},
    ]);

    register_rest_field('note', 'userNoteCount', [
      'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');},
    ]);
  }

  function redirect_subs_to_frontend() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
      wp_redirect(site_url('/'));
      exit;
    }
  }

  function no_subs_admin_bar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
      show_admin_bar(false);
    }
  }

  function our_header_url() {
    return esc_url(site_url('/'));
  }

  function our_header_title() {
    return get_bloginfo('name');
  }

  function our_login_css() {
    wp_enqueue_style('google-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style( 'university_main_styles', get_stylesheet_uri());
  }
   
  function make_note_private($data, $postarr) {
    if ($data['post_type'] == 'note') {
      if (count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']) {
        die('You have reached your note limit.');
      }

      // prevent subscribers to post html
      $data['post_title'] = sanitize_text_field($data['post_title']);
      $data['post_content'] = sanitize_textarea_field($data['post_content']);
    }
    
    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
      $data['post_status'] = 'private';
    }

    return $data;
  }

  add_action('wp_enqueue_scripts', university_files);
  add_action('after_setup_theme', university_features);
  add_action('pre_get_posts', university_adjust_queries);
  add_action('rest_api_init', university_custom_rest);

  add_filter('acf/fields/google_map/api', university_map_key);

  // redirect subscriber accounts out of admin and onto homepage
  add_action('admin_init', redirect_subs_to_frontend);
  add_action('wp_loaded', no_subs_admin_bar);

  // customize login screen
  add_filter('login_headerurl', our_header_url);
  add_filter('login_headertitle', our_header_title);

  // load css in login screen
  add_action('login_enqueue_scripts', our_login_css);

  // force note posts to be private, sanitize and force limit
  // 2 to pass second argument ($postarr) which helps us get the post id to differentiate between old posts and new ones (or between create post and update or delete)
  add_filter('wp_insert_post_data', make_note_private, 10, 2);
?>
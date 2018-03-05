<?php 

function university_register_search() {
  register_rest_route('university/v1', 'search', [
    'methods' => WP_REST_SERVER::READABLE, /* GET */
    'callback' => university_search_results
  ]);
}

function university_search_results($params) {
  $mainQuery = new WP_Query([
    'post_type' => ['post', 'page', 'professor', 'program', 'event', 'campus'],
    's' => sanitize_text_field($params['term'])
  ]);

  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array(),
  );

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();
    $subArrayName;
    $extras = array();
    switch(get_post_type()) {
      case 'post':
        $subArrayName = 'generalInfo';
        $extras['author_name'] =  get_author_name();
        $extras['post_type'] =  'post';
      case 'page':
        $subArrayName = 'generalInfo';
        break;
      case 'program':
        $subArrayName = 'programs';
        $extras['id'] = get_the_ID();
        $relatedCampuses = get_field('related_campus');
        if ($relatedCampuses) {
          foreach($relatedCampuses as $campus) {
            array_push($results['campuses'], [
              'title' => get_the_title($campus),
              'permalink' => get_the_permalink($campus)
            ]);
          }
        }
        
        break;
      case 'event':
        $subArrayName = 'events';
        $extras['month'] = (new DateTime(get_field('event_date')))->format('M');
        $extras['day'] = (new DateTime(get_field('event_date')))->format('d');
        $extras['excerpt'] = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);
        break;
      case 'professor': 
        $subArrayName = 'professors';
        $extras['thumbnail'] = get_the_post_thumbnail_url(0, 'professorLandscape');
        break;
      case 'campus': 
        $subArrayName = 'campuses';
        break;
    }

    $postFields =  array_merge([
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
    ], $extras);

    array_push($results[$subArrayName], $postFields);
  }

  if ($results['programs']) {
    $programsMetaQuery = ['relation' => 'OR'];
    foreach($results['programs'] as $program) {
      array_push($programsMetaQuery, [
        'key' => 'related_programs',
        'compare' => 'LIKE',
        'value' => '"' . $program['id'] . '"'
      ]);
    }
    $programRelationshipQuery = new WP_Query([
      'post_type' => ['professor', 'event'],
      'meta_query' => $programsMetaQuery,
    ]);
  
    while ($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();
      $subArrayName;
      $extras = [];

      switch(get_post_type()) {
        case 'event':
          $subArrayName = 'events';
          $extras['month'] = (new DateTime(get_field('event_date')))->format('M');
          $extras['day'] = (new DateTime(get_field('event_date')))->format('d');
          $extras['excerpt'] = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);
          break;
        case 'professor': 
          $subArrayName = 'professors';
          $extras['thumbnail'] = get_the_post_thumbnail_url(0, 'professorLandscape');
          break;
      }
    
      $postFields =  array_merge([
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ], $extras);
  
      array_push($results[$subArrayName], $postFields);
    }
  }

  foreach(['professors', 'events', 'campuses'] as $postType) {
    $results[$postType] =  array_values(array_unique($results[$postType], SORT_REGULAR));
  }

  return $results;
}

add_action('rest_api_init', university_register_search);

?>
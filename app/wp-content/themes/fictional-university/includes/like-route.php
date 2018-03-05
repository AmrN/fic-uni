<?php

function university_like_routes() {
  register_rest_route('university/v1', 'manageLike', [
    'methods' => 'POST',
    'callback' => create_like,
  ]);

  register_rest_route('university/v1', 'manageLike', [
    'methods' => 'DELETE',
    'callback' => delete_like,
  ]);
}

function create_like($params) {
  if (is_user_logged_in()) {
    $professor_id = sanitize_text_field($params['professorId']);
    $existQuery = new WP_Query([
      'author' => get_current_user_id(),
      'post_type' => 'like',
      'meta_query' => [
        [
          'key' => 'liked_professor_id',
          'compare' => '=',
          'value' => $professor_id,
        ],
      ],
    ]);

    // make sure user hasn't liked professor already and that the id belongs to a professor
    if ($existQuery->found_posts == 0 && get_post_type($professor_id) == 'professor') {  
      return wp_insert_post([
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => '2nd PHP Test',
        'meta_input' => [
          'liked_professor_id' => $professor_id,
        ]
      ]);
    } else {
      die('invalid professor id');
    }
  } else {
    die('Only logged in users can create a like');
  }

}

function delete_like($params) {
  $likeId = sanitize_text_field($params['like']);
  if (get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like') {
    wp_delete_post($likeId, true);
    return 'congrats, like deleted';
  } else {
    die('You do not have permission to delete that');
  }
}

add_action('rest_api_init', university_like_routes);
<?php
  function university_post_types() {
    register_post_type('event', [
      // to grant it speicific roles separately from normal posts
      'capability_type' => 'event',
      'map_meta_cap' => true,

      'supports' => ['title', 'editor', 'excerpt'],
      'rewrite' => [
        'slug' => 'events',
      ],
      'has_archive' => true,
      'public' => true,
      'labels' => [
        'name' => 'Events',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'all_items' => 'All Events',
        'signular_name' => 'Event',
      ],
      'menu_icon' => 'dashicons-calendar',
    ]);
    
    register_post_type('program', [
      'supports' => ['title'],
      'rewrite' => [
        'slug' => 'programs',
      ],
      'has_archive' => true,
      'public' => true,
      'labels' => [
        'name' => 'Programs',
        'add_new_item' => 'Add New Program',
        'edit_item' => 'Edit Program',
        'all_items' => 'All Programs',
        'signular_name' => 'Program',
      ],
      'menu_icon' => 'dashicons-welcome-learn-more',
    ]);

    register_post_type('professor', [
      'show_in_rest' => true,
      'supports' => ['title', 'editor', 'thumbnail'],
      // 'rewrite' => [
      //   'slug' => 'professors',
      // ],
      // 'has_archive' => true,
      'public' => true,
      'labels' => [
        'name' => 'Professors',
        'add_new_item' => 'Add New Professor',
        'edit_item' => 'Edit Professor',
        'all_items' => 'All Professors',
        'signular_name' => 'Professor',
      ],
      'menu_icon' => 'dashicons-awards',
    ]);

    register_post_type('campus', [
      'capability_type' => 'campus',
      'map_meta_cap' => true,
       
      'supports' => ['title', 'editor', 'excerpt'],
      'rewrite' => [
        'slug' => 'campuses',
      ],
      'has_archive' => true,
      'public' => true,
      'labels' => [
        'name' => 'Campuses',
        'add_new_item' => 'Add New Campus',
        'edit_item' => 'Edit Campus',
        'all_items' => 'All Campuses',
        'signular_name' => 'Campus',
      ],
      'menu_icon' => 'dashicons-location-alt',
    ]);

    register_post_type('note', [
      'capability_type' => 'note',
      'map_meta_cap' => true,
      'show_in_rest' => true,
      'supports' => ['title', 'editor'],
      // public false won't show posts in search 
      'public' => false,
      // because public is false, we need to explicitly show the custom post in admin dashboard
      'show_ui' => true,

      'labels' => [
        'name' => 'Notes',
        'add_new_item' => 'Add New Note',
        'edit_item' => 'Edit Note',
        'all_items' => 'All Notes',
        'signular_name' => 'Note',
      ],
      'menu_icon' => 'dashicons-welcome-write-blog',
    ]);

    register_post_type('like', [
      'supports' => ['title',],
      'public' => false,
      // because public is false, we need to explicitly show the custom post in admin dashboard
      'show_ui' => true,

      'labels' => [
        'name' => 'Likes',
        'add_new_item' => 'Add New Like',
        'edit_item' => 'Edit Like',
        'all_items' => 'All Likes',
        'signular_name' => 'Like',
      ],
      'menu_icon' => 'dashicons-heart',
    ]);
  
}

  add_action('init', university_post_types);
?>
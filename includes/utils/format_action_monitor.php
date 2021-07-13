<?php

function jam_cms_format_action_monitor($post){

  $formatted_content = json_decode($post->post_content, true);
  l($formatted_content);

  l($post);
  // Some action types aren't very user friendly,
  // so we mnually gonna change the attributes here if necessary
  if($formatted_content['action_type'] == 'DIFF_SCHEMAS'){  
    
    $formatted_post = [
      'id'              => $post->ID,
      'title'           => 'Theme Settings',
      'description'     => 'Options',
      'actionType'      => 'UPDATE'
    ];

  }elseif($formatted_content['action_type'] == 'NON_NODE_ROOT_FIELDS'){

    $formatted_post = [
      'id'              => $post->ID,
      'title'           => $post->post_title,
      'description'     => 'Options',
      'actionType'      => 'UPDATE'
    ];

  }elseif($formatted_content['graphql_single_name'] == 'menuItem'){

    // Exclude menu item changes (menu should be enough)
    $formatted_post = null;

  }elseif($formatted_content['graphql_single_name'] == 'mediaItem'){

    $formatted_post = [
      'id'              => $post->ID,
      'title'           => $post->post_title ? $post->post_title : $post->post_name,
      'description'     => 'MediaItem',
      'actionType'      => $formatted_content['action_type']
    ];

  }else{

    $formatted_post = [
      'id'              => $post->ID,
      'title'           => ucfirst($post->post_title),
      'description'     => ucfirst($formatted_content['graphql_single_name']),
      'actionType'      => $formatted_content['action_type']
    ];
    
  }

  return $formatted_post;
}
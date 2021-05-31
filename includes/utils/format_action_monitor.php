<?php

function jam_cms_format_action_monitor($post){

  $formatted_content = json_decode($post->post_content, true);

  if($formatted_content['action_type'] == 'DIFF_SCHEMAS'){

    // DIFF_SCHEMAS isn't very editor friendly, so we mnually gonna change the type here
    $formatted_post = [
      'id'              => $post->ID,
      'title'           => 'Theme Settings',
      'description'     => 'options',
      'actionType'      => 'UPDATE'
    ];

  }else{

    $formatted_post = [
      'id'              => $post->ID,
      'title'           => $post->post_title,
      'description'     => $formatted_content['graphql_single_name'],
      'actionType'      => $formatted_content['action_type']
    ];
  }

  return $formatted_post;
}
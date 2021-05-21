<?php

function jam_cms_format_action_monitor($post){

  $formatted_content = json_decode($post->post_content, true);

  $formatted_post = [
    'id'              => $post->ID,
    'title'           => $post->post_title,
    'description'     => $formatted_content['graphql_single_name'],
    'actionType'      => $formatted_content['action_type']
  ];

  return $formatted_post;

}
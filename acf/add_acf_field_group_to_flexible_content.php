<?php

function gcms_add_acf_field_group_to_flexible_content($field_group){

  // Add ACF clone field of module. This will be used for the flexible content element.
  $flexible_content_field_id = gcms_get_acf_field_id('acf-field', 'field_flex');

  $clone_field_key = 'field_' . $field_group->key;

  $clone_field_id = gcms_get_acf_field_id('acf-field', $clone_field_key);
  
  if(!$clone_field_id){
    $args = [
      'key'           =>  $clone_field_key,
      'name'          =>  $clone_field_key, 
      'label'         =>  $field_group->title,
      'parent'        =>  $flexible_content_field_id,
      'type'          => 'clone',
      'parent_layout' => 'layout_' . $field_group->key,
      'clone'         => array(
          0 => $field_group->key,
      )
    ];

    acf_update_field($args);
  }

  // Add clone to flexible content
  $flexible_content_post = get_post($flexible_content_field_id);
  $content = unserialize($flexible_content_post->post_content);

   // Get all acf fields with Block in name and update flexible content altogether
  $all_groups = acf_get_field_groups();
  $layouts = [];

  foreach($all_groups as $group){
    if (strpos($group['title'], 'Block: ') !== false){
      $layouts["layout_" . $group['key']] = [
          "key"     => "layout_" . $group['key'],
          "label"   => $group['title'],
          "name"    => $group['key'],
          "display" => "block",
      ];
    }
  }

  $content['layouts'] = $layouts;

  wp_update_post([
    'ID'            => $flexible_content_field_id,
    'post_content'  => serialize($content)
  ]);

}

?>
<?php

function gcms_add_acf_field_group($module, $options_page = false){
  
  // Loop through fields and create ACF subfields
  $fields = [];

  foreach($module->fields as $field){

    $base_args = [
      'key' => 'field_' . $field->id . '_group_' . $module->name,
      'name' => $field->id,
      'label' => $field->label
    ];

    // Convert JS to ACF type arguments
    $type_args = gcms_format_acf_field($field);

    $args = array_merge($base_args, $type_args);
    array_push($fields, $args);
  }

  // Upsert module
  $group_key = 'group_' . $module->name;
  $group_id = gcms_get_acf_field_id('acf-field-group', $group_key);
  $group_label = 'Block: ' . $module->label;

  $field_group = array(
    'ID' => $group_id ? $group_id : 0,
    'key' => $group_key,
    'title' => $group_label,
    'fields' => $fields,
    'location' => array(
      'group_0' => array(
        'rule_0' => array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'page'
        ),
        'rule_1' => array(
          'param' => 'post_type',
          'operator' => '!=',
          'value' => 'page'
        )
      )
    ),
    'active' => true,
    'style' => 'seamless',
    'position' => 'normal',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
  );

  acf_import_field_group($field_group);

  
  // Add ACF clone field of module. This will be used for flexible content element
  $flexible_content_field_id = gcms_get_acf_field_id('acf-field', 'field_5fa4b6444156f');

  $clone_field_key = 'field_' . $module->name;
  $clone_field_id = gcms_get_acf_field_id('acf-field', $clone_field_key);
  if(!$clone_field_id){
    $args = [
      'key'     =>  $clone_field_key,
      'name'    =>  $module->name, 
      'label'   =>  $group_label,
      'parent'  =>  $flexible_content_field_id,
      'type'    => 'clone',
      'parent_layout' => 'layout_' . $group_key,
      'clone' => array(
          0 => $group_key,
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
          "key"=> "layout_" . $group['key'],
          "label"=> $group['title'],
          "name"=> $group['key'],
          "display"=> "block",
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
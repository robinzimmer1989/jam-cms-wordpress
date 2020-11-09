<?php

function gcms_add_acf_field_group($module, $options_page = false){
  
  // Loop through fields and create ACF subfields
  $fields = [];
  foreach($module->fields as $field){

    if(!is_object($field)){ 
      return;
    }

    $base_args = [
      'key' => 'field_' . $field->id . '_group_' . $module->name,
      'name' => $options_page ? $module->name . '_' . $field->id : $field->id,
      'label' => property_exists($field, 'label') ? $field->label : $field->id
    ];

    // Convert JS to ACF type arguments and prevent non supported field types from being added
    $type_args = gcms_format_acf_field_type($field);

    if($type_args){
      $args = array_merge($base_args, $type_args);
      array_push($fields, $args);
    }
  }

  // Upsert module
  $group_key = 'group_' . $module->name;
  $group_id = gcms_get_acf_field_id('acf-field-group', $group_key);

  // We want to add the 'Block: ' string to flexible content modules, but not option page modules
  // And since the label should be required, we'll check for it too and use the name instead if not exist.
  if(property_exists($module, 'label')){
    $group_label = $options_page ? $module->label : 'Block: ' . $module->label;
  }else {
    $group_label = $options_page ? $module->name : 'Block: ' . $module->name;
  }

  // Add location rules (post vs. option)
  if($options_page){
    $location_rule = array(
			'rule_0' => array('param' => 'options_page', 'operator' => '==', 'value' => 'theme_' . $module->name)
		);
  }else {
    $location_rule = array(
      'rule_0' => array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
      'rule_1' => array('param' => 'post_type', 'operator' => '!=', 'value' => 'page')
    );
  }

  // Create field group
  $field_group = array(
    'ID'                    => $group_id ? $group_id : 0,
    'key'                   => $group_key,
    'title'                 => $group_label,
    'fields'                => $fields,
    'location'              => array(
      'group_0'             => $location_rule
    ),
    'active'                => true,
    'style'                 => 'seamless',
    'position'              => 'normal',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
  );

  acf_import_field_group($field_group);

  // We don't want to add flexible content clone fields for option blocks
  if($options_page){
    return;
  }
  
  // Add ACF clone field of module. This will be used for the flexible content element.
  $flexible_content_field_id = gcms_get_acf_field_id('acf-field', 'field_5fa4b6444156f');

  $clone_field_key = 'field_' . $module->name;
  $clone_field_id = gcms_get_acf_field_id('acf-field', $clone_field_key);
  if(!$clone_field_id){
    $args = [
      'key'           =>  $clone_field_key,
      'name'          =>  $module->name, 
      'label'         =>  $group_label,
      'parent'        =>  $flexible_content_field_id,
      'type'          => 'clone',
      'parent_layout' => 'layout_' . $group_key,
      'clone'         => array(
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
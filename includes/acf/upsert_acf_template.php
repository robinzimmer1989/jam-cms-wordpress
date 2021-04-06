<?php

function jam_cms_upsert_acf_template($template, $post_id){

  $template_key = jam_cms_get_template_key($post_id);

  // Loop through fields and create ACF subfields
  $fields = [];
  foreach($template->fields as $field){
    $field = (object) $field;

    $field_key = "field_{$field->id}_group_{$template_key}";
    
    $base_args = [
      'key'   => $field_key,
      'name'  => $field->id,
      'label' => property_exists($field, 'label') ? $field->label : $field->id
    ];

    // Convert JS to ACF type arguments and prevent non supported field types from being added
    $type_args = jam_cms_format_acf_field_type_for_db($field, $field_key);

    if($type_args){
      $args = array_merge($base_args, $type_args);
      array_push($fields, $args);
    }
  }


  $field_group_key = 'group_' . $template_key;
  $field_group_id  = jam_cms_get_acf_field_id('acf-field-group', $field_group_key);

  if(property_exists($template, 'label')){
    $field_group_label = $template->label;
  }else {
    $field_group_label = $template->id;
  }

  $locations = [[
    'param'     => 'post_type',
    'operator'  => '==',
    'value'     => $template->postTypeID,
  ]];

  if($template->postTypeID == 'page'){
    // TODO: This is not possible at the moment due to a WpGraphQL restriction
    // array_push($locations, [
    //   'param'     => "{$template->postTypeID}_template",
    //   'operator'  => '==',
    //   'value'     => $template->id,
    // ]);
  }

  // Create field group
  $field_group = [
    'ID'                    => $field_group_id ? $field_group_id : 0,
    'key'                   => $field_group_key,
    'title'                 => $field_group_label,
    'fields'                => $fields,
    'location'              => [$locations],
    'active'                => true,
    'style'                 => 'seamless',
    'position'              => 'normal',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'menu_order'            => 0,
    'description'           => '',
    'hide_on_screen'        => [
      0 => 'the_content',
      1 => 'excerpt',
      2 => 'discussion',
      3 => 'comments',
      4 => 'slug',
      5 => 'author',
      6 => 'format',
      8 => 'tags',
      9 => 'send-trackbacks'
    ],
    'show_in_graphql'       => true,
    'graphql_field_name'    => 'acf'
  ];

  acf_import_field_group($field_group);
}

?>
<?php

function jam_cms_upsert_acf_template($template, $post_type_id){

  $template_key = '';

  if(property_exists($template, 'id') && $post_type_id){

      if($template->id == 'archive'){
          $template_key = "page-archive-{$post_type_id}";

      }else{
          $template_key = "{$post_type_id}-{$template->id}";
      }
  }

  // Loop through fields and create ACF subfields
  $fields = [];
  foreach($template->fields as $field){
    $field = (object) $field;

    $field_key = "field_{$field->id}_group_{$template_key}";

    $label = property_exists($field, 'label') ? $field->label : $field->id;
    
    $base_args = [
      'key'   => $field_key,
      'name'  => $field->id,
      'label' => htmlspecialchars($label)
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
    $field_group_title = $template->label;
  }else {
    $field_group_title = $template->id;
  }
  
  // initialize default GraphQL type variables for WPGraphQL ACF plugin version 0.5
  $graphql_types = [];

  if($template->id == 'archive'){

    // Generate capitalized template name
    $template_name = ucfirst($post_type_id);

    // Archive pages always belong to the post type page
    $locations = [[
      'param'     => 'post_type',
      'operator'  => '==',
      'value'     => 'page',
    ]];

    // The postTypeID is the indicator for the template id
    array_push($locations, [
      'param'     => "page_template",
      'operator'  => '==',
      'value'     => "template-archive-{$post_type_id}.php"
    ]);

    // The template name follows the structure Template_ArchivePost
    $graphql_types[] = "Template_Archive{$template_name}";
  
  }else{

    // Generate capitalized template name
    $template_name = ucfirst($template->id);

    // Assign template to post type
    $locations = [[
      'param'     => 'post_type',
      'operator'  => '==',
      'value'     => $post_type_id,
    ]];

    // At the moment the only post type support for templates is 'page'
    if($post_type_id == 'page'){

      array_push($locations, [
        'param'     => "page_template",
        'operator'  => '==',
        'value'     => $template->id == 'default' ? 'default' : "template-{$template->id}.php",
      ]);

      if($template->id == 'default'){
        // The template name for the default page template is set to 'DefaultTemplate'
        $graphql_types[] = 'DefaultTemplate';
      }else{
        // The template name follows the structure 'Template_[Sidebar]'
        $graphql_types[] = "Template_{$template_name}";
      }

    }else{
      $graphql_types[] = ucfirst($post_type_id);
    }
  }

  // Create field group
  $field_group = [
    'ID'                    => $field_group_id ? $field_group_id : 0,
    'key'                   => $field_group_key,
    'title'                 => $field_group_title,
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
    'show_in_graphql'                       => true,
    'graphql_field_name'                    => 'acf',
    'map_graphql_types_from_location_rules' => true,
    'graphql_types'                         => $graphql_types,
  ];

  acf_import_field_group($field_group);
}
<?php

function jam_cms_add_acf_field_group_for_menu($menu_field){

  $template_key = "menu-{$menu_field->id}";

  $sub_fields = jam_cms_generate_sub_fields_recursively($menu_field->fields, $template_key);

  // Loop through fields and create ACF subfields
  $formatted_fields = [];
  foreach($menu_field->fields as $field){
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
      array_push($formatted_fields, $args);
    }
  }

  $field_group_key = 'group_' . $template_key;
  $field_group_id  = jam_cms_get_acf_field_id('acf-field-group', $field_group_key);
  $field_group_title = 'Menu: ' . property_exists($menu_field, 'label') ? $menu_field->label : $menu_field->id;

  $menu = wp_get_nav_menu_object($menu_field->id);

  $locations = [
    [
      'param' => 'nav_menu_item',
      'operator' => '==',
      'value' => "{$menu->term_id}",
    ]
  ];

  // Create field group
  $field_group = [
    'ID'                    => $field_group_id ? $field_group_id : 0,
    'key'                   => $field_group_key,
    'title'                 => $field_group_title,
    'fields'                => $formatted_fields,
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
      7 => 'categories',
      8 => 'tags',
      9 => 'send-trackbacks'
    ]
  ];

  acf_import_field_group($field_group);

}
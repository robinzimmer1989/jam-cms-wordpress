<?php

function gcms_get_acf_id($field_type, $field_name){
  global $wpdb;

  return $wpdb->get_var("
      SELECT ID
      FROM $wpdb->posts
      WHERE post_type='$field_type' AND post_excerpt='$field_name';
  ");
}

function gcms_add_acf_field_group($module, $options_page = false){

  $name = $module->name;

  // This must be consistent with automatic flexible content 'modules' function
  $module_title = str_replace('Module: ', '', $name);
  $module_name = strtolower(preg_replace('/[^\w-]+/','-', $module_title));

  $field_group_id = gcms_get_acf_id('acf-field-group', $name);

  if(!$field_group_id){
    $field_group_args = array(
      'post_title'     => 'Module: ' . $name,
      'post_excerpt'   => $name,
      'post_name'      => $name,
      'post_date'      => date( 'Y-m-d H:i:s' ),
      'comment_status' => 'closed',
      'post_status'    => 'publish',
      'post_type'      => 'acf-field-group',
    );

    if($options_page){
      $field_group_args['post_content'] = serialize([
        'style' => 'seamless',
        'location' => [
          [
            [
              'param' => 'options_page',
              'operator' => '==',
              'value' => $options_page
            ]
          ]
        ]
      ]);
    }else{
      $field_group_args['post_content'] = serialize([
        'style' => 'seamless',
        'location' => [
          [
            [
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'post',
            ],
            [
              'param' => 'post_type',
              'operator' => '!=',
              'value' => 'post',
            ]
          ]
        ]
      ]);
    }
  
    $field_group_id  = wp_insert_post( $field_group_args );
  }


  if($options_page){

    $field_options_group_id = gcms_get_acf_id('acf-field', $options_page);

    if(!$field_options_group_id){
      $args = [
        'key' => $options_page,
        'label' => $options_page,
        'name' => $options_page,
        'parent' => $field_group_id,
        'type' => 'group'
      ];

      $field_group = acf_update_field($args);
      $field_group_id = $field_group['ID'];
    }
  }

  $fields = $module->fields;

  foreach($fields as $field){

    $field_key = gcms_get_acf_id('acf-field', $field->id);

    if(!$field_key){

      $args = [
        'key' => $field->id,
        'label' =>  $field->label,
        'name' => $field->id,
        'parent' => $field_group_id
      ];

      // Map through different fields types and convert JS to ACF schema

      if($field->type == 'image'){
        $args['type'] = $field->type;

      }elseif($field->type == 'number'){
        $args['type'] = $field->type;

      }elseif($field->type == 'text'){
        $args['type'] = $field->type;

      }elseif($field->type == 'wysiwyg'){
        $args['type'] = $field->type;

      }elseif($field->type == 'select'){
        $args['type'] = 'select';

        $choices = [];
        foreach($field->options as $option){
          $choices[$option->value] = $option->name;
        }
        $args['choices'] = $choices;

      }elseif($field->type == 'image'){
        

      }elseif($field->type == 'email'){
        

      }elseif($field->type == 'url'){
        

      }elseif($field->type == 'file'){
        

      }elseif($field->type == 'oEmbed'){
        

      }elseif($field->type == 'checkbox'){
        

      }elseif($field->type == 'radio'){
        

      }elseif($field->type == 'postObject'){
        

      }elseif($field->type == 'link'){
        

      }elseif($field->type == 'map'){
        

      }elseif($field->type == 'colorPicker'){
        

      }elseif($field->type == 'repeater'){
        

      }elseif($field->type == 'accordion'){
        

      }else {
        $args['type'] = null;
      }

      if($args['type']){
        acf_update_field($args);
      }
    }
  }

  return $test;
}

?>
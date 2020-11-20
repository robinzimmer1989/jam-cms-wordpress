<?php

/**
 * gcms_add_acf_flexible_content
 *
 * Create ACF field group with flexible content field
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @return void
 */

function gcms_add_acf_flexible_content(){
  
  // TODO: WordPress is throwing an error on create site related to an empty clone field

  $field_group = [
    'key'                   => 'group_flex',
    'title'                 => 'Flexible Content',
    'fields'                => [
      [
        'key'         => 'field_flex',
        'name'        => 'flex',
        'label'       => 'Flexible Content',
        'type'        => 'flexible_content',
        'sub_fields'  => []
      ]
    ],
    'location'              => array(
      'group_0'             => array(
        'rule_0' => array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
        'rule_1' => array('param' => 'post_type', 'operator' => '!=', 'value' => 'page')
      )
    ),
    'active'                => true,
    'style'                 => 'seamless',
    'position'              => 'normal',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
  ];

  acf_import_field_group($field_group);
}

?>
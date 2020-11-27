<?php

/**
 * jam_cms_add_acf_flexible_content
 *
 * Create ACF field group with flexible content field
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @return void
 */

function jam_cms_add_acf_flexible_content(){

  $field_id = jam_cms_get_acf_field_id('acf-field-group', 'group_flex');

  $field_group = [
    'ID'                    => $field_id ? $field_id : 0,
    'key'                   => 'group_flex',
    'title'                 => 'Flexible Content',
    'fields'                => [
      [
        'key'               => 'field_flex',
        'name'              => 'flex',
        'label'             => 'Flexible Content',
        'type'              => 'flexible_content',
        'sub_fields'        => []
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
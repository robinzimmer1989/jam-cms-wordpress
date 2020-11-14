<?php

function gcms_add_acf_flexible_content(){

  $field_group = [
    'key'                   => 'group_flexible_content',
    'title'                 => 'Flexible Content',
    'fields'                => [
      [
        'key'   => 'field_flexible_content',
        'name'  => 'flexible_content',
        'label' => 'Flexible Content',
        'type'  => 'flexible_content'
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
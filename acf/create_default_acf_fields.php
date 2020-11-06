<?php

  function gcms_create_default_acf_fields(){
    $field_group = array(
      'key' => 'group_blocks',
      'title' => 'Blocks',
      'fields' => [
        [
          'key' => 'field_5fa4b6444156f',
          'name' => 'flexible_content',
          'label' => 'Blocks',
          'type' => 'flexible_content'
        ]
      ],
      'location' => array(
        'group_0' => array(
          'rule_0' => array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'page'
          )
        )
      ),
      'active' => true,
      'style' => 'default',
      'position' => 'normal',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'menu_order' => 0,
      'description' => '',
      'hide_on_screen' => array(
        0 => 'the_content',
        1 => 'excerpt',
        2 => 'discussion',
        3 => 'comments',
        4 => 'slug',
        5 => 'author',
        6 => 'format',
        7 => 'categories',
        8 => 'tags',
        9 => 'send-trackbacks',
      )
    );
    
    acf_import_field_group($field_group);
  }

?>
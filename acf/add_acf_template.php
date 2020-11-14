<?php

  function gcms_add_acf_template($title, $name){

    $template_id = gcms_get_acf_field_id('acf-field-group', 'group_template_' . $name);

    // Create flexible content element
    $field_group = array(
      'ID'    => $template_id ? $template_id : 0,
      'key' => 'group_template_' . $name,
      'title' => 'Template: ' . $title,
      'fields' => [
        [
          'key'     => 'field_flexible_content_group_template_' . $name,
          'name'    => 'flexible_content',
          'label'   => 'Flexible Content',
          'type'    => 'clone',
          'clone'   => array(
            0 => 'group_flexible_content',
          )
        ]
      ],
      'location' => array(
        'group_0' => array(
          'rule_0' => array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => $name
          )
        )
      ),
      'active' => true,
      'style' => 'seamless',
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
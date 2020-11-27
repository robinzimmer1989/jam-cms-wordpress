<?php

/**
 * jam_cms_add_acf_template
 *
 * Add template field group with flexible content field as clone
 *
 * @date	20/11/20
 * @since	0.0.1
 *
 * @param	string $title The title of the template
 * @param	string $name The post type name (= unique id)
 * @return	void
 */

function jam_cms_add_acf_template($title, $name){

  $template_id = jam_cms_get_acf_field_id('acf-field-group', 'group_template-' . $name);

  // Create flexible content element
  $field_group = array(
    'ID'    => $template_id ? $template_id : 0,
    'key' => 'group_template-' . $name,
    'title' => 'Template: ' . $title,
    'fields' => [
      [
        'key'     => 'field_flex_group_template-' . $name,
        'name'    => 'field_flex_group_template',
        'label'   => 'Flexible Content',
        'type'    => 'clone',
        'clone'   => array(
          0 => 'group_flex',
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
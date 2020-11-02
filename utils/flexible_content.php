<?php

if( function_exists('acf_add_local_field_group') ):

    $layouts = [];
    $modules = acf_get_field_groups();

    foreach($modules as $module):

        $title = $module['title'];

        if (strpos($title, 'Module: ') !== false) :

            $title = str_replace('Module: ', '', $title);
            $slug = strtolower(preg_replace('/[^\w-]+/','-', $title));

            $layouts['layout_' . $slug] = [
                'key' => 'layout_' . $slug,
                'label' => $title,
                'name' => $slug,
                'display' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_' . $slug,
                        'label' => $title,
                        'name' => $slug,
                        'type' => 'clone',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'clone' => array('group_' . $slug),
                        'style' => 'seamless',
                        'layout' => 'block',
                        'prefix_label' => 0,
                        'prefix_name' => 0,
                    ),
                ),
                'min' => '',
                'max' => '',
            ];
        endif;
                
    endforeach;

    acf_add_local_field_group(array(
        'ID' => 1,
        'key' => 'group_modules',
        'title' => 'Modules',
        'fields' => array(
            array(
                'key' => 'modules',
                'label' => 'Modules',
                'name' => 'modules',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layouts' => $layouts,
                'button_label' => 'Add Content',
                'min' => '',
                'max' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'all',
                ), 
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'label',
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
        ),
        'active' => true,
        'description' => '',
    ));

endif;

?>

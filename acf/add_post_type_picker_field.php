<?php

class acf_field_post_selector extends acf_field {
	
	function __construct() {
		$this->name = 'collection';
		$this->label = __('Post Type Selecor', 'acf-collection');
    $this->category = 'choice';
		$this->defaults = array();
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-collection'),
		);
		
    parent::__construct();
	}
	
	function render_field_settings( $field ) {}
	
	function render_field( $field ) {
		$field_value = $field['value'];
							
    $field['choices'] = array();

    $post_types = get_post_types([], 'objects');
    $custom_post_types = get_option('cptui_post_types') ? get_option('cptui_post_types') : [];
    $all_post_types = array_merge($post_types, $custom_post_types);

    $items = [];
    foreach ( $all_post_types as $post_type ) {
      $post_type = (object) $post_type;

      if ($post_type->publicly_queryable && $post_type->name != 'attachment') {
          array_push($items, $post_type);
      }
    }
							
		echo '<select name="' . $field['name'] . '" class="acf-collection">';

				if ( ! empty( $items ) ) {
					foreach ( $items as $choice ) {
						$field['choices'][ $choice->name ] = $choice->name;
						$field['choices'][ $choice->label ] = $choice->label;

						echo '<option  value="' . $field['choices'][ $choice->name ] . '" ' . selected($field_value, $field['choices'][ $choice->name ], false) . ' >' . $field['choices'][ $choice->label ] . '</option>' ;
					}
				}
		echo '</select>';

	}
}

new acf_field_post_selector();

?>
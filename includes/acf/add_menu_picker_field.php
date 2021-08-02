<?php

class acf_field_menu_picker extends acf_field {
	
	function __construct() {
		$this->name = 'menu';
		$this->label = __('Menu Picker', 'acf-menu');
    $this->category = 'choice';
		$this->defaults = array();
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-menu'),
		);
		
    parent::__construct();
	}
	
	function render_field_settings( $field ) {}
	
	function render_field( $field ) {
		$field_value = $field['value'];
							
		$field['choices'] = array();
		$menus = wp_get_nav_menus();
							
		echo '<select name="' . $field['name'] . '" class="acf-menu">';

				if ( ! empty( $menus ) ) {
					foreach ( $menus as $choice ) {
						$field['choices'][ $choice->menu_id ] = $choice->term_id;
						$field['choices'][ $choice->name ] = $choice->name;
						
						// Prevent rendering translated menus here by checking for '___'
						if(!strpos($choice->name, '___')){
							echo '<option  value="' . $field['choices'][ $choice->menu_id ] . '" ' . selected($field_value, $field['choices'][ $choice->menu_id ], false) . ' >' . $field['choices'][ $choice->name ] . '</option>' ;
						}
					}
				}
		echo '</select>';

	}
}

new acf_field_menu_picker();
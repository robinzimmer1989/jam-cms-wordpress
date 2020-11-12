<?php

function gcms_format_acf_field_type_for_frontend($field){
  
  $args = [];

  if($field->type == 'repeater'){
    $args['items'] = gcms_get_repeater_items_recursively($field);
  }

  return $args;
}

?>
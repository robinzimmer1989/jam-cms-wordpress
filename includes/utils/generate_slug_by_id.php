<?php

function jam_cms_generate_slug_by_id($post_id){
  return str_replace(home_url(), '', get_permalink($post_id));
}

?>
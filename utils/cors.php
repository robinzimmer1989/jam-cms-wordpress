<?php

function gcms_add_cors_http_header(){
  header("Access-Control-Allow-Origin: *");
}
add_action('init','gcms_add_cors_http_header');

?>
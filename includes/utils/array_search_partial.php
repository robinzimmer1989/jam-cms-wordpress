<?php

function jam_cms_array_search_partial($arr, $keyword) {
  foreach($arr as $index => $string) {
    if (strpos($string, $keyword) !== FALSE){
      return true;
    }
  }
}
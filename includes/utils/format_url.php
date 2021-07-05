<?php

function jam_cms_format_url($url){
  
  $site_url = get_site_url();
  $url = str_replace($site_url, '', $url);

  return $url;
}
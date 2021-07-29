<?php

function jam_cms_format_language($language){
  return [
    'id'      => $language['id'],
    'name'    => $language['name'],
    'slug'    => $language['slug'],
    'locale'  => $language['locale'],
    'flag'    => $language['flag'],
  ];
}
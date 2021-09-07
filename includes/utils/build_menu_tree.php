<?php

function jam_cms_build_menu_tree($fields, array &$elements, $parentId = 0) {
  $branch = array();

  foreach ($elements as &$element) {
      if ($element->menu_item_parent == $parentId) {
        $children = jam_cms_build_menu_tree($fields, $elements, $element->ID);
        if ($children) {
            $element->children = $children;
        }
        $branch[] = jam_cms_format_menu_item($fields, $element);
        unset($element);
      }
  }
  return $branch;
}
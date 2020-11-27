<?php

function jam_cms_build_menu_tree(array &$elements, $parentId = 0) {
  $branch = array();

  foreach ($elements as &$element) {
      if ($element->menu_item_parent == $parentId) {
        $children = jam_cms_build_menu_tree($elements, $element->ID);
        if ($children) {
            $element->children = $children;
        }
        $branch[] = jam_cms_format_menu_item($element);
        unset($element);
      }
  }
  return $branch;
}

?>
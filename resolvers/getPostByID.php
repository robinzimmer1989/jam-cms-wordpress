<?php

function gcms_resolver_getPostByID($siteID, $postID){

  $post = get_post($postID);

  if($post){
    $formattedPost = gcms_formatPost($siteID, $post);

    // Get flexible content fields and format
    $content = get_fields($postID);
    $formattedModules = [];

    if($content && $content['modules']){
      foreach($content['modules'] as $module){
        $fields = [];

        foreach($module as $key => $value ){
          if($key != 'acf_fc_layout'){
            array_push($fields, array(
              'id' => $key,
              'value' => $value
            ));
          }
        }

        array_push($formattedModules, [
          'fields' => $fields,
          'name' => $module['acf_fc_layout']
        ]);
      }
    }

    $formattedPost['content'] = $formattedModules;

    return $formattedPost;
  }

  return 'Post not found';

}

?>
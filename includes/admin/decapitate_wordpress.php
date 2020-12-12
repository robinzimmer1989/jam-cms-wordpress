<?php

add_action("template_redirect", function() {
  wp_redirect('/wp-admin');
});

?>
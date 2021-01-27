<?php

add_filter( 'wp_mail_from', 'jam_cms_change_from_email_address', 10, 1);
function jam_cms_change_from_email_address($from_email){
  $parts = explode( '@', $from_email );
	return "no-reply@{$parts[1]}";
}

add_filter( 'wp_mail_from_name', 'jam_cms_change_from_name', 10, 1);
function jam_cms_change_from_name($from_name){
  return get_bloginfo('name');
}

add_filter( 'wp_mail_content_type','jam_cms_set_email_content_type' );
function jam_cms_set_email_content_type(){
	return "text/html";
}

add_filter("retrieve_password_message", 'jam_cms_change_password_reset_email', 10, 4);
function jam_cms_change_password_reset_email($message, $key, $user_login, $user) {	

  $site_url = get_option('site_url');
  $site_name = get_bloginfo('name');

  $message = "
<html>
<head></head>
<body>
<p>Someone has requested a password reset for the following account:</p>
<p>User email: {$user->user_email}</p>
<p>If this was an error, please disregard this email and there is no further action needed.</p>
<p>To reset your password, visit the following address:</p>
<p>{$site_url}/jam-cms/?action=reset&key={$key}&login={$user->user_login}</p>
<p>Thanks!</p>
<p>The {$site_name} Team</p>
</body>
</html>";

	return $message;
};

add_filter( 'wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3 );
function custom_wp_new_user_notification_email( $email, $user, $site_name ) {
	
	$key = get_password_reset_key($user);
	$site_url = get_option('site_url');

  $message = "
<html>
<head></head>
<body>
<p>Welcome to {$site_name}</p>
<p>Click on the link below to set your password:</p>
<p>{$site_url}/jam-cms?action=reset&key={$key}&email={$user->user_login}</p>

<p>The {$site_name} Team</p>
</body>
</html>";

  $email['subject'] = "{$site_name} Account";
	$email['message'] = $message;
	
  return $email;
}

?>
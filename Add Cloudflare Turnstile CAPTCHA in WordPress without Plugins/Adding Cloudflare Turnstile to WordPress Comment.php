/*
 * Adding Cloudflare Turnstile to WordPress Comment
 * https://redpishi.com/wordpress-tutorials/cloudflare-turnstile-captcha-wordpress/
 */	
function is_valid_captcha($captcha) {
	
   if (!$captcha) {
     return false;
   }
   $secretKey = cloudflare_key()[1];
   $ip = $_SERVER['REMOTE_ADDR'];

   $url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
   $data = array('secret' => $secretKey, 'response' => $captcha, 'remoteip' => $ip);
	
	$options = array(
		'http' => array(
		'method' => 'POST',
		'content' => http_build_query($data))
	);
	
	$stream = stream_context_create($options);
	
	$result = file_get_contents(
			$url_path, false, $stream);
	
	$response =  $result;
   
   $responseKeys = json_decode($response,true);
	  if(intval($responseKeys["success"]) !== 1) {
		   return false;
	  } else { 
		  return true;
}
}
	
add_action('init', function(){
	if (!is_user_logged_in() ) {
    add_action('pre_comment_on_post', function(){
		$recaptcha = $_POST['cf-turnstile-response'];
	if (empty($recaptcha))
    wp_die( __("<b>ERROR:</b> please select <b>I'm not a robot!</b><p><a href='javascript:history.back()'>« Back</a></p>"));
	else if (!is_valid_captcha($recaptcha))
    wp_die( __("<b>please select I'm not a robot!</b>"));
		
		} );

    add_filter('comment_form_defaults',function ($submit_field) {

		    $submit_field['submit_field'] = '<div class="cf-turnstile" data-sitekey="'.cloudflare_key()[0].'"></div><br>'.$submit_field['submit_field'];
    return $submit_field;	
	});
}	
	});

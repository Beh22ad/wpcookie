/*
 * Adding Cloudflare Turnstile to WordPress Registration page
 * https://redpishi.com/wordpress-tutorials/cloudflare-turnstile-captcha-wordpress/
 */	

add_action('register_form', function() {
    echo '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    echo '<div class="cf-turnstile" data-sitekey="'.cloudflare_key()[0].'"></div>';
	echo'<style>p.message.register {
    width: 324px;
}</style>';
});
add_action('registration_errors', function($errors, $user_login, $user_email) {
    if (!empty($_POST['cf-turnstile-response'] )) {
		
	$captcha = 	$_POST['cf-turnstile-response'];
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
        
        
        
        if (   intval($responseKeys["success"]) == 1   ) {
            return $errors;
        } else {
            return new WP_Error('Captcha Invalid', __('Captcha Invalid! Please check the captcha!'));
        }
    } else {
        return new WP_Error('Captcha Invalid', __('Captcha Invalid! Please check the captcha!'));
    }
}, 10, 3);

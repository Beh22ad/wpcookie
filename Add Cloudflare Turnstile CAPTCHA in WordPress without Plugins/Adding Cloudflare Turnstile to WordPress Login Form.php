/*
 * Adding Cloudflare Turnstile to Login Form by wpcookie
 * https://redpishi.com/wordpress-tutorials/cloudflare-turnstile-captcha-wordpress/
 */	
function login_style() {
    wp_register_script('login-recaptcha', 'https://challenges.cloudflare.com/turnstile/v0/api.js', false, NULL);
    wp_enqueue_script('login-recaptcha');
	echo "<style>p.submit, p.forgetmenot {margin-top: 10px!important;}.login form{width: 303px;} div#login_error {width: 322px;}</style>";
}
add_action('login_enqueue_scripts', 'login_style');

add_action('login_form', function(){
	echo '<div class="cf-turnstile" data-sitekey="'.cloudflare_key()[0].'"></div>';
	} );

add_action('wp_authenticate_user', function($user, $password) {
	$captcha=$_POST['cf-turnstile-response'];
	if (!$captcha) {
     return new WP_Error('Captcha Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>'));
	   die();
           exit;
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
		   return new WP_Error('Captcha Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>'));
		  die();
		   exit;
	  } else { 
		  return $user;
}
	
	} , 10, 2);

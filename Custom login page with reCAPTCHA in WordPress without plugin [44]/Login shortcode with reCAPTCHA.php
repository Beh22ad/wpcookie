/** 
* Login shortcode with reCAPTCHA in WordPress by WPCookie
* https://redpishi.com/wordpress-tutorials/login-shortcode-with-recaptcha/
*/
function my_recaptcha_key(){
	$sitekey= "000000000000000000000000000";
	$secretkey= "00000000000000000000000000000000000";
	return explode(",", $sitekey.",".$secretkey ); 	
}

function add_recaptcha_on_login_page() {
    echo '<div class="g-recaptcha brochure__form__captcha" data-sitekey="'.my_recaptcha_key()[0].'"></div>';
}
add_action('login_form','add_recaptcha_on_login_page');
function login_style() {
    wp_register_script('login-recaptcha', 'https://www.google.com/recaptcha/api.js', false, NULL);
    wp_enqueue_script('login-recaptcha');
	echo "<style>p.submit, p.forgetmenot {margin-top: 10px!important;}.login form{width: 303px;} div#login_error {width: 322px;}</style>";
}
add_action('login_enqueue_scripts', 'login_style');
function captcha_login_check($user, $password) {
    if (!empty($_POST['g-recaptcha-response'])) {
        $secret = my_recaptcha_key()[1];
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $rsp = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha .'&remoteip='. $ip);
        $valid = json_decode($rsp, true);
        if ($valid["success"] == true) {
            return $user;
        } else {
            return new WP_Error('Captcha-Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>'));
        }
    } else {
        return new WP_Error('Captcha-Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>'));
    }
}
add_action('wp_authenticate_user', 'captcha_login_check', 10, 2);
add_shortcode( 'login-form', 'loginform_func' );
function loginform_func( $atts ) {
	$atts = shortcode_atts( array(
		'logo' => '1', 
	), $atts, 'login-form' );
	
	if ( is_user_logged_in() ) {
	return "<p class='wpcookie-logged-user'>You are already logged in.</p>";
}		
		$content =  wp_login_form( 
							array( 
								'echo' => false ,
								'redirect'       => get_home_url() ,
								'label_username' => __( 'Your Username ' ),
								'label_password' => __( 'Your Password' ),
								'label_remember' => __( 'Remember Me' )
			              )
			)."<a href=".esc_url( wp_lostpassword_url() )." style='font-size: 1rem;width: 100%;margin-left: 25px;'>Lost your password?</a>";	
wp_register_script('login-recaptcha', 'https://www.google.com/recaptcha/api.js', false, NULL);
    wp_enqueue_script('login-recaptcha');
	$style1 = "<style>p.submit, p.forgetmenot {margin-top: 10px!important;}.login form{width: 303px;} div#login_error {width: 322px;}</style>";	
$content = str_replace('<input type="submit" ','<div class="g-recaptcha brochure__form__captcha" data-sitekey="'.my_recaptcha_key()[0].'"></div><input type="submit" ',$content);
$error = "";
if (isset($_GET['reason'])) {
	$er = "";
	
    if($_GET['reason'] == 'Captcha-Invalid') {
		$er = "Captcha Invalid! Please check the captcha!";
	} else if ($_GET['reason'] == 'invalid_username' || $_GET['reason'] == 'incorrect_password' || $_GET['reason'] == 'empty_username' || $_GET['reason'] == 'empty_password') {
		$er = "Invalid username or password, try again";
	}
	$error = '<p class="wpcookie_er">'.$er.'</p>';
} 
$style2 = '
<style>
.wpcookie-login-form label {
    display: block;
    font-size: 1rem;
	color: dimgrey;
}
.wpcookie-login-form input[type="text"], .wpcookie-login-form input[type="password"] {
    height: 2rem;
    width: 303px;
    border: 1px solid #e7e7e7;
    border-radius: 5px;
	background-color: #ffffff26;
}
.wpcookie-login-form p.wpcookie_er {
	font-size: 1rem;
    color: #b11414;
}
.wpcookie-login-form input[type="submit"] {
    color: white;
    background-color: #4e8ef5;
    outline: none;
    border: navajowhite;
    padding: 0.7rem;
    width: 303px;
    margin-top: 15px;
    border-radius: 5px;
    transition: all 0.5s ease;
    cursor: pointer;
}
.wpcookie-login-form input[type="submit"]:hover {
    background-color: #1d3eab;
}
.wpcookie-login-form {
    width: 350px;
    margin: auto;
    background-color: #ffffff;
    border-radius: 5px;
    padding: 20px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    flex-wrap: nowrap;
    align-items: center;
}
p.wpcookie-logged-user {
    width: fit-content;
    font-weight: bold;
    border-radius: 5px;
    padding: 15px;
    background-color: #ffffffc2;
    color: black!important;
    margin: auto;
}
</style>
';	
$logo_url = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0]) ;
$logo ='<img src="'.$logo_url.'" alt="logo" style="padding: 15px; height: 106px;">';
	if ($atts["logo"] == 0 ) { $logo = ""; }	
	return "{$style2}{$style1}<div class='wpcookie-login-form'>{$logo}{$error}{$content}</div>";	
}
add_filter('login_redirect', 'my_login_redirect', 10, 3);
function my_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if (is_wp_error($user)) {
        $error_types = array_keys($user->errors);
        $error_type = 'both_empty';
        if (is_array($error_types) && !empty($error_types)) {
            $error_type = $error_types[0];
        }
		if(isset($_SERVER['HTTP_REFERER']) ) {
					$location =  strtok($_SERVER['HTTP_REFERER'], '?');
			if ( str_contains( $location, "wp-login.php") ) {return;}
        wp_redirect( $location . "?login=failed&reason=" . $error_type ); 
        exit;
		} else return;

    } else {
        return home_url();
    }
}

/*
*Add reCaptcha to Lost Password Form Without Plugin
*/
function recaptcha() {
    echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    echo '<div class="g-recaptcha brochure__form__captcha" data-sitekey="'.my_recaptcha_key()[0].'"></div>';
	echo '<style>p.message {width: 324px;}</style>';
}
add_action('lostpassword_form', 'recaptcha');

function captcha_lostpassword_check($errors) {
    if (!empty($_POST['g-recaptcha-response'])) {
        $secret = my_recaptcha_key()[1];
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $rsp = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha .'&remoteip='. $ip);
        $valid = json_decode($rsp, true);
        if ($valid["success"] == true) {
           
        } else {
            $errors->add('Captcha Invalid', __('Captcha Invalid! Please check the captcha!'));
        }
    } else {
        $errors->add('Captcha Invalid', __('Captcha Invalid! Please check the captcha!'));
    }
}
add_action( 'lostpassword_post', 'captcha_lostpassword_check', 10, 1 );

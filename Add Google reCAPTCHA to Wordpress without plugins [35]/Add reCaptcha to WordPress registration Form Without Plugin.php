/*
*Protect WordPress Site Registration Form with reCaptcha
*/
function recaptcha_register_form() {
    echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    echo '<div class="g-recaptcha brochure__form__captcha" data-sitekey="'.my_recaptcha_key()[0].'"></div>';
	echo'<style>p.message.register {
    width: 324px;
}</style>';
}
add_action('register_form', 'recaptcha_register_form');

function captcha_registration_check($errors, $user_login, $user_email) {
    if (!empty($_POST['g-recaptcha-response'])) {
        $secret = my_recaptcha_key()[1];
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $rsp = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha .'&remoteip='. $ip);
        $valid = json_decode($rsp, true);
        if ($valid["success"] == true) {
            return $errors;
        } else {
            return new WP_Error('Captcha Invalid', __('Captcha Invalid! Please check the captcha!'));
        }
    } else {
        return new WP_Error('Captcha Invalid', __('Captcha Invalid! Please check the captcha!'));
    }
}
add_action('registration_errors', 'captcha_registration_check', 10, 3);

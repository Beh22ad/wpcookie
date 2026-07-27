/*
*Add reCAPTCHA to WordPress Comment Section
*/
add_action( 'wp_head', function(){
wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
} );
function add_google_recaptcha($submit_field) {
    $submit_field['submit_field'] = '<div class="g-recaptcha" data-sitekey="'.my_recaptcha_key()[0].'"></div><br>' . $submit_field['submit_field'];
    return $submit_field;
}
if (!is_user_logged_in()) {
    add_filter('comment_form_defaults','add_google_recaptcha');
}
function is_valid_captcha($captcha) {
$captcha_postdata = http_build_query(array(
                            'secret' => my_recaptcha_key()[1],
                            'response' => $captcha,
                            'remoteip' => $_SERVER['REMOTE_ADDR']));
$captcha_opts = array('http' => array(
                      'method'  => 'POST',
                      'header'  => 'Content-type: application/x-www-form-urlencoded',
                      'content' => $captcha_postdata));
$captcha_context  = stream_context_create($captcha_opts);
$captcha_response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify" , false , $captcha_context), true);
if ($captcha_response['success'])
    return true;
else
    return false;
}
 
function verify_google_recaptcha() {
$recaptcha = $_POST['g-recaptcha-response'];
if (empty($recaptcha))
    wp_die( __("<b>ERROR:</b> please select <b>I'm not a robot!</b><p><a href='javascript:history.back()'>« Back</a></p>"));
else if (!is_valid_captcha($recaptcha))
    wp_die( __("<b>Go away SPAMMER!</b>"));
}
if (!is_user_logged_in()) {
    add_action('pre_comment_on_post', 'verify_google_recaptcha');
}

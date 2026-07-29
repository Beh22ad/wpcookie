/*
 * login shortcode by WPCookie [wpcookie-login]
 * https://redpishi.com/wordpress-tutorials/change-admin-url-without-plugins/
 */
add_shortcode( 'wpcookie-login', 'drlogin_shortcode' );
function drlogin_shortcode($atts) {
	$atts = shortcode_atts( array(
	'num' => '0',
	), $atts, 'drlogin' );
	$num = $atts["num"];

	if (  is_user_logged_in() ) { return "<p class='wpcookie-logged-user'>You are logged in.</p>"; }
$style= '
<style>
</style>
';
$buffer = '<div style="max-width:350px; margin:10px auto; ">'.get_Ajax_login_form(0).get_Ajax_login_form(1).'</div><script>'.get_Ajax_login_form(2).'</script>'.$style;

	return $buffer;

}
// // Handle Ajax login requests
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login');

function ajax_login() {
	// first form (get user & pass)
	if ( !isset( $_POST['password'] ) || !isset( $_POST['username'] ) ) {
		echo json_encode(array('loggedin' => '0', 'message' =>'<p style="color: #b30e0e;font-size: 0.9em;">Username and password cannot be empty.</p>' ));
		die();
	}


	$username = $_POST['username'];
	$password = $_POST['password'];
	$user = wp_authenticate($username, $password);
	$id = $user->ID;

	if (is_wp_error($user)){
		$err = $user->get_error_message();
		echo json_encode(array('loggedin' => '0', 'message' =>'<p style="color: #b30e0e;font-size: 0.9em;">'.$err.'</p>' ));
		die();
	} else {
		wp_set_current_user($id);
		wp_set_auth_cookie($id);
		$is_admin = $admin_url = '';
		if ( user_can( $id, 'manage_options' ) ) {
			$is_admin = 1;
			$admin_url = get_admin_url();
		}
		echo json_encode( array( 'loggedin' => '2', 'message' => '<p style="color: #005500;font-size: 0.9em;">Login was successful, please wait...</p>', 'admin' => $is_admin , 'admin_url' => $admin_url  ) );
		die();
	}
}
// get ajax login form

function get_Ajax_login_form($n) {

	$form_logo = '
	<div class="logo_wrapper" style="display: grid; justify-content: center;"></div>
	';

    $form = '
    <div id="ajax-login-form">
	<form id="login-form"  class="login-form" enctype="multipart/form-data" onsubmit=" return false;">
	<span id="status"> </span>
		<div class="inside_form">

			<input type="text" name="username" id="login-username" placeholder="Username" required>
			<input type="password" name="password" id="login-password" placeholder="Password" required>

		<input type="hidden" name="action" value="ajax_login">
		<input type="submit" id="submit_login_btn" name="login" value="Login" >
		</div>

		<div class="lost_pass" style=" margin-top: 10px; font-size: 0.9em; ">
		<a href="'.esc_url( wp_lostpassword_url() ).'" style="margin-left: 0px;">Forgot your password?</a></div>
	</form>
</div>
<style>
:root {
    --post-table-color: #4682b4;
}
div#ajax-login-form input {
    height: 2.5rem;
    border: none;
    border-radius: 3px;
}
div#ajax-login-form input[type="text"], div#ajax-login-form input[type="password"] {
    border: 1px solid #d8d6d6;
    padding: 2px 11px;
}
div#ajax-login-form a {
    text-decoration: none;
}
div.ajax-login-form{
max-width: 330px;
}
form#login-form div.inside_form {
    max-width: 330px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
input#submit_login_btn {
    background-color: var(--post-table-color);
    transition: all 0.3s ease;
    cursor: pointer;
	color: #fff;
}
input#submit_login_btn:hover {
    transform: translateY(-1px);
}
input#submit_login_btn:disabled {
    background-color: gray;
}
input#resend_mail {
	width: 180px;
    color: var(--post-table-color);
    border: 1px solid var(--post-table-color);
    border-radius: 3px;
    background-color: white;
    cursor: pointer;
}
input#resend_mail:disabled {
    color: gray;
    border: 1px solid gray;
}
form#login-form #status {
	max-width: 330px;;
	display: block;
	margin-top: 15px;
}
.\32 -col-r {
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    flex-wrap: nowrap;
}
input#code::placeholder {
    text-align: center;
    margin: 0 -30px 0 0px;
}
input#code {
    padding-left: 30px;
}

</style>
';

$js = '
document.querySelector("form#login-form").addEventListener("submit", function(e) {

    let currentForm = e.target;
    currentForm.querySelector("#submit_login_btn").disabled = true;
    currentForm.querySelector("#status").innerHTML = `<p style="font-size: 0.9em;">Please wait ...</p>`;
    let myForm = currentForm;
    var formdata = new FormData(myForm);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "'.admin_url( "admin-ajax.php" ).'", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var result = JSON.parse(xhr.responseText);
                currentForm.querySelector("#status").innerHTML = result.message;
                currentForm.querySelector("#submit_login_btn").disabled = false;
                if (result.loggedin == "2") {
                    if (result.admin == "1") {
                        window.location.href = result.admin_url;
                    } else {
                        location.reload();
                    }


                } else {

                }
            } else {
                currentForm.querySelector("#status").innerHTML = `<p style="color: #b30e0e;font-size: 0.9em;">There seems to be an issue in establishing a connection with the server. Please inform the website administrator. </p>`;
                currentForm.querySelector("#submit_login_btn").disabled = false;
            }
        }
    };
    xhr.send(formdata);
});
';
    if ( $n == '1' ){
        return $form;
    } else if ( $n == '2' ){
        return $js;
    } else if ( $n == '0' ){
        return $form_logo;
    }
}

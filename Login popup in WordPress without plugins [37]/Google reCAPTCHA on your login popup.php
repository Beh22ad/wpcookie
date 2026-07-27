function my_recaptcha_key(){
	$sitekey= "00000000000000000";
	$secretkey= "000000000000000";
	return explode(",", $sitekey.",".$secretkey ); 	
}


add_action( 'wp_head', function(){

	if ( !is_user_logged_in() ) 
	{
		wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
	}

} );


function login_style() {
    wp_register_script('login-recaptcha', 'https://www.google.com/recaptcha/api.js', false, NULL);
    wp_enqueue_script('login-recaptcha');
	echo "<style>p.submit, p.forgetmenot {margin-top: 10px!important;}.login form{width: 303px;} div#login_error {width: 322px;}</style>";
}

add_action('login_enqueue_scripts', 'login_style');


function add_recaptcha_on_login_page() {
    echo '<div class="g-recaptcha brochure__form__captcha" data-sitekey="'.my_recaptcha_key()[0].'"></div>';
}
add_action('login_form','add_recaptcha_on_login_page');

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
            return new WP_Error('Captcha Invalid', __('<center style="color: #b30e0e;font-size: 0.9em;">Please check the captcha!</center>'));
        }
    } else {
        return new WP_Error('Captcha Invalid', __('<center style="color: #b30e0e;font-size: 0.9em;">Captcha Invalid! Please check the captcha!</center>'));
    }
}
add_action('wp_authenticate_user', 'captcha_login_check', 10, 2);





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


function get_Ajax_login_form($n) {


    $form = '<div id="ajax-login-form"><form id="login-form"  class="login-form" enctype="multipart/form-data" onsubmit=" return false;"><span id="status"> </span><div class="inside_form"><input type="text" name="username" id="login-username" placeholder="Username" required><input type="password" name="password" id="login-password" placeholder="Password" required><div class="g-recaptcha brochure__form__captcha" data-sitekey="'.my_recaptcha_key()[0].'"></div><input type="hidden" name="action" value="ajax_login"><input type="submit" id="submit_login_btn" name="login" value="Login" ></div><div class="lost_pass" style=" margin-top: 10px; font-size: 0.9em; "><a href="'.esc_url( wp_lostpassword_url() ).'" style="margin-left: 0px;">Forgot your password?</a></div></form></div>';

$style = '
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
        return $style;
    } 
}






add_action('wp_body_open', function() { ?> 

<style>
.blur-bg {
    inset: 0;
    z-index: 100;
    background-color: black;
    opacity: 0;
	position: fixed;
	pointer-events: none;
	transition: 200ms ease-in-out;
}
.blur-bg.active {
    background-color: black;
    opacity: 0.5;	
       pointer-events: all;
    }	
.modal {
max-height: 85%;
overflow: auto;
position: fixed;
top: 50%;
left: 50%;
box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
transform: translate(-50%, calc(-50% - 100px));
opacity: 0;
z-index: 101;
background: rgba(255, 255, 255, 1);
border-radius: 3px;
width: fit-content;;
max-width: 90%;
transition: 200ms ease-in-out;
pointer-events: none;
}
.modal.active {
	transform: translate(-50%, -50%);
	opacity: 1;
	pointer-events: all;	
}
.modal-header {
	padding: 5px 15px 0px 15px;
	margin: 0px 0px -12px 0px;
	display: flex;
	justify-content: space-between;
	align-items: center;
}
.modal-title {
	font-size: 1.2rem;
}
.modal .close {
    cursor: pointer;
}
button.close-button {
cursor: pointer;
border: none;
outline: none;
background: none;
font-size: 1.5rem;
color: #9e9e9e;
padding: 0;
transition: all 0.5s ease;
margin-right: -5px;
}
button.close-button:hover {
color: black;
}
.modal-body {
	padding: 10px 15px;
}
.modal.hiden {
		display: none;
}
	form#loginform {
    margin: 18px 10px -24px 10px;
}
form#loginform label {
    display: block;
}
form#loginform input[type=text], form#loginform input[type=password] {
    width: 300px;
    height: 3rem;
}
a.login, a.logout-link {
    text-decoration: none;
}
#loginform input#wp-submit {
    height: 3rem;
    width: 7rem;
    cursor: pointer;
}
a[href='#login#'] {
    display: none;
}
</style>
 
<script>
class loginPopUp { 
    constructor(openType, Pclass, id) { 
		this.id = id;
		this.Pid = "a"+id;
		this.pop = '<div class="modal hiden" id="'+ this.Pid +'"> <div class="modal-header" > <div class="modal-title" ></div> <button class="close-button close" > &times; </button> </div> <div class="modal-body" >   <div style="max-width:350px; margin:10px auto; "> <?= get_Ajax_login_form(1) ?> </div>     </div> </div>';	
		document.body.insertAdjacentHTML("beforeend", this.pop);
		this.bg = '<div class="blur-bg close"></div>';
		if ( !document.querySelector('.blur-bg') ) { document.body.insertAdjacentHTML("beforeend", this.bg); }
        this.Pclass = Pclass;
        this.Type = openType;
        if (this.Type == "click" ) {
            this.btnPopShow();
        } else if (this.Type == "time" ) {
            this.showTimePop();           
        }
	
	}
	
	closePopUp(e) {
			setTimeout( () => {      
				document.querySelector("#"+this.Pid+".active")?.classList.remove("active");
				document.querySelector(".blur-bg").classList.remove('active');
			} , e * 1000 ); }

			btnPopShow(){  
			document.querySelectorAll("."+this.Pclass).forEach((bot) => {
			bot.addEventListener("click", ()=>{
			document.querySelector("#"+this.Pid)?.classList.remove("hiden");
			setTimeout( () => { 
			document.querySelector("#"+this.Pid).classList.add("active");	
			this.settings();
		}, 100)
		})
		});
		}
	showTimePop(){
	let storage = sessionStorage.getItem(this.Pid) ? sessionStorage.getItem(this.Pid) : 0;
	if (storage > 1 ) return;
	setTimeout( () => {      
		document.querySelector("#"+this.Pid)?.classList.remove("hiden");
	} , 1000 );      
		setTimeout( () => {      
		document.querySelector("#"+this.Pid).classList.add("active");
		this.settings();			
		sessionStorage.setItem(this.Pid, parseInt(storage) + 1);			
	} , this.Pclass * 1000 );
	}
	settings(){
		[...document.querySelectorAll(".close")]?.forEach(  e => { e.addEventListener("click", e => { this.closePopUp(0) }) }, { once: true });
		document.querySelector(".blur-bg").classList.add('active');
		document.body.addEventListener('click', e => {
		if (!document.querySelector('.modal.active')?.contains(event.target)) {
		this.closePopUp(0);
	}}, { once: true })

		}
}
 
</script> 
<?php });
add_shortcode( 'login', 'loginpopup_func' );
function loginpopup_func( $atts ) {
	$atts = shortcode_atts( array(
		'type' => 'click', 
		'open' => 'login',
		'content' => '111',
	), $atts, 'login' );
	
	if (isset($_SERVER['HTTP_REFERER'])) {$THE_REFER=$_SERVER['HTTP_REFERER'];} else { $THE_REFER = get_home_url() ;}

		
		//~ $content =  wp_login_form( 
							//~ array( 
								//~ 'echo' => false ,
								//~ 'redirect'       => $THE_REFER ,
								//~ 'label_username' => __( 'Your Username ' ),
								//~ 'label_password' => __( 'Your Password' ),
								//~ 'label_remember' => __( 'Remember Me' )
			              //~ )
			//~ )."<br><a href=".esc_url( wp_lostpassword_url() )." style='margin-left: 10px;'>Lost your password?</a>";	
		
		$content =  '<div style="max-width:350px; margin:10px auto; ">'.get_Ajax_login_form(1).'</div>';
		$newScript = '<script>setTimeout( () => {'.get_Ajax_login_form(2).'},1500)</script>';
		$newStyle = get_Ajax_login_form(0);


	$popup ='<script>
	document.addEventListener("DOMContentLoaded", function(){
    new loginPopUp("click", "login", "111")
});	
</script>';

	
	$loginTXT = ( !is_user_logged_in() ) ? 'Log in' : 'Log out';
	$loginBTN = '<a href="#l#" class="login menu-link">'.$loginTXT.'</a>';	
	
if (is_user_logged_in()) { return wp_loginout( $_SERVER['REQUEST_URI'], false ).'<script>[...document.querySelectorAll("a")].forEach( e => { if (e.href.includes("action=logout") ) { e.classList.add("logout-link"); e.classList.add("menu-link");  } } )</script>'; }
	

	return "{$popup}{$loginBTN}{$newStyle}{$newScript}";	
	
}

function my_dynamic_menu_items( $menu_items ) {
    $placeholders = array(
        '#login#' => array(
            'shortcode' => 'login',
            'atts' => array(), // Shortcode attributes.
            'content' => '', // Content for the shortcode.
        ),
    );
    foreach ( $menu_items as $menu_item ) {

        if ( isset( $placeholders[ $menu_item->url ] ) ) {

            global $shortcode_tags;

            $placeholder = $placeholders[ $menu_item->url ];

            if ( isset( $shortcode_tags[ $placeholder['shortcode'] ] ) ) {

                $menu_item->title = call_user_func( 
                    $shortcode_tags[ $placeholder['shortcode'] ]
                    , $placeholder['atts']
                    , $placeholder['content']
                    , $placeholder['shortcode']
                );
            }
        }
    }

    return $menu_items;
}
add_filter( 'wp_nav_menu_objects', 'my_dynamic_menu_items' );

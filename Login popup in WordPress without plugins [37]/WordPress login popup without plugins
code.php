add_action('wp_body_open', function() { ?> 

<style>
.blur-bg {
    inset: 0;
    z-index: 99;
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
z-index: 100;
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
		this.pop = '<div class="modal hiden" id="'+ this.Pid +'"> <div class="modal-header" > <div class="modal-title" ></div> <button class="close-button close" > &times; </button> </div> <div class="modal-body" ></div> </div>';	
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
		const observer = new MutationObserver(list => {  this.closePopUp(6); observer.disconnect();});
		observer.observe(document.querySelector("#"+this.Pid+".active .modal-body"), {attributes: true, childList: true, subtree: true });
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

		
		$content =  wp_login_form( 
							array( 
								'echo' => false ,
								'redirect'       => $THE_REFER ,
								'label_username' => __( 'Your Username ' ),
								'label_password' => __( 'Your Password' ),
								'label_remember' => __( 'Remember Me' )
			              )
			)."<br><a href=".esc_url( wp_lostpassword_url() )." style='margin-left: 10px;'>Lost your password?</a>";	


	$popup ='<script>
	
	document.addEventListener("DOMContentLoaded", function(){
	
	setTimeout( () => {new loginPopUp("click", "login", "111");
	document.querySelector("#a'.$atts["content"].' div.modal-body").innerHTML = `'.$content.'`;
	} , 1000  );	
    
});
</script>';

	
	$loginTXT = ( !is_user_logged_in() ) ? 'Log in' : 'Log out';
	$loginBTN = '<a href="#l#" class="login menu-link">'.$loginTXT.'</a>';	
	
if (is_user_logged_in()) { return wp_loginout( $_SERVER['REQUEST_URI'], false ).'<script>[...document.querySelectorAll("a")].forEach( e => { if (e.href.includes("action=logout") ) { e.classList.add("logout-link"); e.classList.add("menu-link");  } } )</script>'; }
	

	return "{$popup}{$loginBTN}";	
	
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


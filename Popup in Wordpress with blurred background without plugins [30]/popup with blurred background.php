add_action('wp_footer', function() { if (!is_admin()) {



// Put the popup code here 
      
    }});
add_action('wp_head', function() { ?> 

<style>
.blur-bg {
    inset: 0;
    z-index: 60;
    backdrop-filter: blur(0px);
	-webkit-backdrop-filter: blur(0px);
	position: fixed;
	pointer-events: none;
	transition: 200ms ease-in-out;
}
.blur-bg.active {
 	   backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);	
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
background: rgba(255, 255, 255, 0.9);
border-radius: 3px;
width: min(450px , 90%)
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
</style>
 
<script>
class redPishiPopUp { 
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
add_shortcode( 'popup', 'popup_func' );
function popup_func( $atts ) {
	$atts = shortcode_atts( array(
		'type' => 'time', 
		'open' => '3',
		'content' => '111',
	), $atts, 'popup' );

	$popup ='<script>new redPishiPopUp("'. $atts["type"] .'", "'. $atts["open"] .'", "'. $atts["content"] .'");</script>';
	$content = (!get_post_status($atts["content"])) ? "reusable block not found!" : apply_filters( 'the_content', get_post( $atts["content"] )->post_content);
	$insertContentScript = '<script>document.querySelector("#a'.$atts["content"].' div.modal-body").innerHTML = `'.$content.'`; </script>';	
	return "{$popup}{$insertContentScript}";	
}	

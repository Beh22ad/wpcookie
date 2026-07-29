function cloudflare_key(){
	$sitekey= "00000000000000000000000";
	$secretkey= "000000000000000000000";
	return [$sitekey,$secretkey]; 	
}

add_action("wp_head", function(){	
	wp_enqueue_script('cloudflare-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js');
	} );
	

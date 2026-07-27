function my_recaptcha_key(){
	$sitekey= "00000000000000000000000000000";
	$secretkey= "00000000000000000000000000000";
	return explode(",", $sitekey.",".$secretkey ); 	
}

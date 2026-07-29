/*
 * Stop WordPress Spam Comments by WPCookie
 * https://redpishi.com/wordpress-tutorials/stop-wordpress-spam-comments/
 */	
add_action('pre_comment_on_post', function(){
	if (!is_user_logged_in() ) {
$wpcookie = $_POST['wpcookie'];	
if (empty($wpcookie))
wp_die( __("<b>ERROR:</b> Please don't spam! , go back and reload the page and give it another shot.<p><a href='javascript:history.back()'>« Back</a></p>"));
else if ( $wpcookie != "ok" )
wp_die( __("You wrote your comment too fast, go back and reload the page and give it another shot. <p><a href='javascript:history.back()'>« Back</a></p>"));
	  }
	} 

);

add_filter('comment_form_defaults',function ($submit_field) {		   
		$submit_field["fields"]["author"] = $submit_field["fields"]["author"].
		'<input id="wpcookie" name="wpcookie" type="hidden" value="1"><script>
		setTimeout( () => { document.querySelector("input#wpcookie").value = "ok" } , 5000 )
		</script>';
		
return $submit_field;	
});

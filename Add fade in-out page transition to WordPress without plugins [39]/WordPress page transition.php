/** 
* WordPress page transition by WPCookie
* https://redpishi.com/wordpress-tutorials/page-transition-wordpress/
*/

function wpcookie_page_transition(){
	/* put your conditional tags here */	 
	$condition = true;
	/**********************************/
	return $condition;
}
add_filter( 'wp_head', function ( ) { if (wpcookie_page_transition()) { ?>
<style>
body {
	-webkit-transition: 1s all ease-out;
	-moz-transition: 1s all ease-out;
	-ms-transition: 1s all ease-out;
	-o-transition: 1s all ease-out;
	transition: 1s all ease-out;
}

body.opacity {
	-webkit-transition: 0s all ease-out;
	-moz-transition: 0s all ease-out;
	-ms-transition: 0s all ease-out;
	-o-transition: 0s all ease-out;
	transition: 0s all ease-out;
	opacity: 0;
}
.nice_page_transition_opacity {
	opacity: 0;
}

</style>	

<?php } });

add_filter( 'body_class', function( $classes ) {
	return array_merge( $classes, array( 'opacity' ) );
} );


add_filter( 'wp_footer', function ( ) { if (wpcookie_page_transition()) { ?>

<script>

	document.addEventListener("DOMContentLoaded", function(){
	
    [...document.querySelectorAll('a:not([href^=\\#])')].forEach( function(e){ e.addEventListener("click", () => {
				document.querySelector('body').classList.add('nice_page_transition_opacity');   
		setTimeout(function(){ 
		document.querySelector('body').classList.remove('nice_page_transition_opacity'); }, 2000);
	});

	})
setTimeout(function(){ document.querySelector('body').classList.remove('opacity'); }, 1000);
});
	
	
	
</script>
<?php } } );

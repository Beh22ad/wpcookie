/*
 * Save Form Submissions in Database by WPCookie
 * https://redpishi.com/wordpress-tutorials/save-form-in-database/
 */
function email_post_type() {
	$labels = array(
		'name'                  => _x( 'Emails', 'Post Type General Name', 'redpishi_com' ),
		'singular_name'         => _x( 'Email', 'Post Type Singular Name', 'redpishi_com' ),
		'menu_name'             => __( 'Emails', 'redpishi_com' ),
		'name_admin_bar'        => __( 'Email', 'redpishi_com' ),
		'search_items'          => __( 'Search Emails', 'redpishi_com' ),
	);
	$args = array(
		'label'                 => __( 'Email', 'redpishi_com' ),
		'description'           => __( 'Email information page.', 'redpishi_com' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor'),		
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-email-alt2',
		'show_in_admin_bar'     => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'map_meta_cap'     	    => true,
		'capability_type'       => 'page',
		'capabilities' 			=> ['create_posts'       => false,],	
	);
	register_post_type( 'email', $args );
}
add_action( 'init', 'email_post_type', 0 );





/*
 * Save emails that failed to send 
 * */
add_action( 'wp_mail_failed', function ( $wp_error ) {
	$subject = $wp_error->error_data["wp_mail_failed"]["subject"] .' - Failed';
	$message = $wp_error->error_data["wp_mail_failed"]["message"];	
	$post_data = array(
		'post_title' => $subject,
		'post_content' => $message,	
		'post_status' => 'publish',
		'post_type'	 => 'email',
	);
	$post_id = wp_insert_post( $post_data );
} , 10, 1 );  



/*
 * Save emails that have been sent successfully
 * */
add_action( 'wp_mail_succeeded', function ( $mail_data ) {
	$subject = $mail_data["subject"] .' - Succeeded';
	$message = $mail_data["message"];
	$post_data = array(
		'post_title' => $subject,
		'post_content' => $message,	
		'post_status' => 'publish',
		'post_type'	 => 'email',
	);
	$post_id = wp_insert_post( $post_data );
} );

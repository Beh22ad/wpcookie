/**
 * Save Contact Form 7 Data to Google Sheets free
 * https://redpishi.com/wordpress-tutorials/cf7-to-google-sheets/
 */
function send_cf7_email_to_api($contact_form) {

    /***********************/
    $google_apps_script_url = 'YOUR_APPS_SCRIPT_URL';
    /***********************/
    
    $submission = WPCF7_Submission::get_instance();   
    if ($submission) {
        $posted_data = $submission->get_posted_data();      
        $clean_body = implode("\n", $posted_data);      
        $data = array('email_body' => $clean_body);      
        $response = wp_remote_post($google_apps_script_url, array(
            'method'    => 'POST',
            'body'      => wp_json_encode($data),
            'headers'   => array(
                'Content-Type' => 'application/json',
            ),
        ));
        if (is_wp_error($response)) {
            error_log('CF7 Email API Error: ' . $response->get_error_message());
        }
    }
}
add_action('wpcf7_before_send_mail', 'send_cf7_email_to_api');

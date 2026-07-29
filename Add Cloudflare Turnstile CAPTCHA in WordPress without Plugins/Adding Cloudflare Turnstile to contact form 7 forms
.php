/*
First, edit the form you want to add Cloudflare Turnstile to and add the following snippet before the submit button:

[c][text c]
/*

add_action("wp_footer", function(){ ?>
    <style>
        .wpcf7 input[name="c"] {
            display: none;
        }
        .wpcf7 .cf-turnstile {
            margin-top: -10px;
        }
    </style>
<?php });

function add_custom_element_before_submit($form) {
    $cf = '<div class="cf-turnstile" data-sitekey="' . cloudflare_key()[0] . '"></div>';
    $form = preg_replace('/\[\s*c\s*\]/', $cf, $form);    
    return $form;
}
add_filter('wpcf7_form_elements', 'add_custom_element_before_submit');

function validate_turnstile($result, $tag) {
    $turnstile_token = isset($_POST['cf-turnstile-response']) ? sanitize_text_field($_POST['cf-turnstile-response']) : '';
    $ct = "c";
    if (empty($turnstile_token)) {
        $result->invalidate($ct, 'You must complete the Turnstile challenge to submit this form.');
        // Mark as spam since no token was provided
        add_filter('wpcf7_spam', function() { return true; }, 100, 2);
        return $result;
    }

    $secret_key = cloudflare_key()[1];
    $remote_ip = $_SERVER['REMOTE_ADDR'];

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'body' => array(
            'secret' => $secret_key,
            'response' => $turnstile_token,
            'remoteip' => $remote_ip,
        ),
    ));

    if (is_wp_error($response)) {
        $result->invalidate($ct, 'The Turnstile token is invalid. Please try again.');
        // Mark as spam for invalid token
        add_filter('wpcf7_spam', function() { return true; }, 100, 2);
        return $result;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!$data['success']) {
        $result->invalidate($ct, 'There was an error validating the Turnstile challenge. Please try again.');
        // Mark as spam if validation failed
        add_filter('wpcf7_spam', function() { return true; }, 100, 2);
        return $result;
    }

    return $result;
}
add_filter('wpcf7_validate', 'validate_turnstile', 10, 2);

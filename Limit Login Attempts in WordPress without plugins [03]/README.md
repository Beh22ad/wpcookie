# Limit Login Attempts in WordPress Without a Plugin

This tutorial provides a code-based method to limit login attempts and block suspicious activities in WordPress, helping to prevent **brute force attacks** without using any plugins.

---

## Why Limit Login Attempts?

The WordPress login page is a primary target for hackers. They often use automated scripts to try thousands of password combinations—this is called a **brute force attack**.

WordPress does not prevent these attacks by default. As a webmaster, you need to secure your login page yourself. Two common methods are:
1. Using **CAPTCHA** on the login page.
2. **Limiting login attempts** and blocking suspicious activity (the method covered here).

---

## How to Limit Login Attempts in WordPress

### 1. Copy the Code into Your functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file and paste the code below.

> **Important:** You **must** use a child theme. Otherwise, your changes will be lost when the parent theme is updated.
> *Alternative:* Use the **Code Snippets** plugin to insert this code safely.

```php
// Limit login attempts by WPcookie
// https://redpishi.com/wordpress-tutorials/limit-login-attempts-wordpress-without-plugin/

function check_attempted_login( $user, $username, $password ) {
    $try_limit = 4;
    if ( get_transient( 'attempted_login_'.$username ) ) {
        $datas = get_transient( 'attempted_login_'.$username );

        if ( $datas['tried'] >= $try_limit ) {
            $until = get_option( '_transient_timeout_' . 'attempted_login_'.$username );
            $time = time_to_go( $until );

            return new WP_Error( 'too_many_tried',  sprintf( __( '<strong>ERROR</strong>: You have reached authentication limit, you will be able to try again in %1$s.' ) , $time ) );
        }
    }

    return $user;
}
add_filter( 'authenticate', 'check_attempted_login', 30, 3 ); 

function login_failed( $username ) {
    $ban_duration = 1;  // hour(s)

    if ( get_transient( 'attempted_login_'.$username ) ) {
        $datas = get_transient( 'attempted_login_'.$username );
        $datas['tried']++;

        if ( $datas['tried'] <= 3 )
            set_transient( 'attempted_login_'.$username, $datas , $ban_duration * 3600 );
    } else {
        $datas = array(
            'tried'     => 1
        );
        set_transient( 'attempted_login_'.$username, $datas , $ban_duration * 3600 );
    }
}
add_action( 'wp_login_failed', 'login_failed', 10, 1 ); 

function time_to_go($timestamp)
{
    // converting the mysql timestamp to php time
    $periods = array(
        "second",
        "minute",
        "hour",
        "day",
        "week",
        "month",
        "year"
    );
    $lengths = array(
        "60",
        "60",
        "24",
        "7",
        "4.35",
        "12"
    );
    $current_timestamp = time();
    $difference = abs($current_timestamp - $timestamp);
    for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i ++) {
        $difference /= $lengths[$i];
    }
    $difference = round($difference);
    if (isset($difference)) {
        if ($difference != 1)
            $periods[$i] .= "s";
            $output = "$difference $periods[$i]";
            return $output;
    }
}
```

> **Code Credit:** This is an extended version of the original code from PHPPOT.

---

### 2. Customize the Code (Optional)

You can adjust two key variables to fit your security needs:

| Variable | Purpose | Recommendation |
| :--- | :--- | :--- |
| **`$try_limit`** | Number of failed login attempts before blocking. | Set between **3 and 5**. |
| **`$ban_duration`** | How long the user is blocked (in hours). | Default is **1 hour**. |

After changing these values, update the `functions.php` file and test the result. For example, with the default settings, a user is blocked for **1 hour** after **4 failed attempts**.

---

## Code Explanation

This code works using three main functions:

1. **`login_failed`**  
   - Triggered after each failed login attempt.  
   - Creates a **transient** (temporary database entry) called `attempted_login_username` to store the number of failed attempts and the block duration.

2. **`check_attempted_login`**  
   - Checks the transient data before authenticating a user.  
   - If the number of attempts exceeds the `$try_limit`, the user is blocked and shown a time-to-wait message.

3. **`time_to_go`**  
   - A helper function that converts the block timestamp into a human-readable format (e.g., "15 minutes", "2 hours").

---

## Frequently Asked Questions

### What is the difference between limiting login attempts and using two-factor authentication (2FA)?

- **Limiting login attempts** restricts *how many times* a user can try to log in before being temporarily locked out. This helps prevent brute force attacks.
- **Two-factor authentication (2FA)** adds an extra layer of security by requiring a second verification code (sent to a mobile device or email) in addition to the password.

For the best protection, it is recommended to **use both methods** together.

### Can I still access my website if I accidentally lock myself out?

**Yes.** If you lock yourself out, you can regain access by:
- Using your hosting control panel or **FTP** to access your theme's `functions.php` file.
- Temporarily removing or commenting out the code to unlock all users.
- Logging in, then re-adding the code.

### Does this code work with a custom login page or other login security plugins?

This code uses the `authenticate` hook. If you are using other code (like reCAPTCHA) that also uses the same hook, there might be a **conflict**. To resolve this, you would need to combine both functions into a single callback for the `authenticate` hook.
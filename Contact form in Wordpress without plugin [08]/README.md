# Add a Contact Form in WordPress Without Plugins (Free)

This tutorial teaches you how to create a fully functional, customizable contact form in WordPress without using any third-party plugins. The form includes a **honeypot** anti-spam technique and uses AJAX for a smooth user experience.

---

## Introduction

WordPress does not include a default contact form feature. While plugins like Contact Form 7 are popular, they often load resources (scripts and styles) on **all** pages, potentially slowing down your site. This custom solution loads only on the pages where you place the form.

---

## Step 1: Add Form Processor to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the full code below and paste it into the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
/*
 * Contact form by WPCookie
 * Update: https://redpishi.com/wordpress-tutorials/contact-form-wordpress-without-plugins-free/
 */
add_action('wp_ajax_submitmyform','submitmyform');
add_action('wp_ajax_nopriv_submitmyform','submitmyform');

function submitmyform(){
    $admin_email = get_bloginfo('admin_email');

    $system = array();

    // Honeypot: Check for hidden field
    if ($_POST['website'] != '') {
        $system['statuse']='er';
        $system['reply']='Suspicious activity was detected, That\'s all we know.';
        echo json_encode($system, JSON_UNESCAPED_UNICODE);
        exit; die();
    }

    // Security: Check for double quotes
    foreach($_POST as $key => $value) {
        if (strpos($value, '"') !== FALSE) {
            $system['statuse']='er';
            $system['reply']='The character entered in the form is not acceptable, please contact the site support.';
            echo json_encode($system, JSON_UNESCAPED_UNICODE);
            exit; die();
        }
    }

    // Sanitize email
    if (!empty($_POST["email"])) {
        $email = sanitize_email($_POST['email']);
    } else {
        $email = $admin_email;
    }

    // Prepare email headers
    $domain = parse_url(get_site_url(), PHP_URL_HOST);
    $site_name = get_bloginfo('name');
    $siteURL = get_site_url();
    $frommyform = 'info@'.substr($siteURL, strpos($siteURL, ".") + 1);
    $headersmyform = "From: $frommyform\n";
    $headersmyform .= "Reply-To: $email\r\n";
    $headersmyform .= "MIME-Version: 1.0\r\n";
    $headersmyform .= "Content-type: text/html; charset=UTF-8\r\n";

    // Form processing function
    function form1($admin_email, $headersmyform) {
        unset($_POST['formid'], $_POST['website'], $_POST['action']);
        $system = array();
        $post = array();

        foreach($_POST as $key => $value) {
            if(strpos($value, "\n") !== FALSE) {
                $post[] = $key.': <b>'.nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8')).'</b>';
            } else {
                if ((strpos($value, "@") !== FALSE)) {
                    $post[] = $key.': <b>'.nl2br(filter_var($value, FILTER_SANITIZE_EMAIL)).'</b>';
                } else {
                    $post[] = $key.': <b>'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'</b>';
                }
            }
        }

        $Agent = $_SERVER['HTTP_USER_AGENT'];
        preg_match('!\(.*?\)!',$Agent, $userAgent);
        $message = join("<br />",$post);
        $message .= "<br><br>---------\<br>";
        $message .= "UserAgent: ".str_replace(array("(", ")"), "", $userAgent[0])."<br>";
        $message .= "User IP: ".$_SERVER['REMOTE_ADDR']."<br>";
        $subject = 'Contact Form';

        if ( wp_mail( $admin_email, $subject, $message, $headersmyform ) ) {
            $system['statuse']='ok';
            $system['reply']='Thank you for getting in touch! <br>We appreciate you contacting us. One of our colleagues will get back in touch with you soon! Have a great day!';
            echo json_encode($system, JSON_UNESCAPED_UNICODE);
            exit; die();
        } else {
            $system['statuse']='er';
            $system['reply']='Sorry, There is a problem in sending your form, please email us your message.';
            echo json_encode($system, JSON_UNESCAPED_UNICODE);
            exit; die();
        }
    }

    // Check form ID and process
    if ($_POST['formid']) {
        $id = $_POST['formid'];
        if ($id == '1001') {
            form1($admin_email, $headersmyform);
        } else {
            $system['statuse']='er';
            $system['reply']='The form is not authorized, please contact the site support.';
            echo json_encode($system, JSON_UNESCAPED_UNICODE);
            exit; die();
        }
    }
}
```

### Customizing the Form Processor (Optional)

- **Change Recipient Email:** The form sends emails to the site admin's email by default. To change this, replace:
  ```php
  $admin_email = get_bloginfo('admin_email');
  ```
  with:
  ```php
  $admin_email = 'youremail@gmail.com';
  ```
- **Change Success/Error Messages:** Edit the `$system['reply']` strings in the code where `'statuse'` is set to `'ok'` or `'er'`.

---

## Step 2: Insert HTML and JavaScript Code into Your Page

1. Create a new page or edit an existing one where you want the form (e.g., a "Contact Us" page).
2. Add an **HTML block** and paste the following complete code into it. Publish/update the page.

```html
<form id="contactForm" onsubmit="submitCform(); return false;">

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Your Name" required>

    <!-- Honeypot field: Do not remove or make visible -->
    <label id="website" for="website">website:</label>
    <input type="text" id="website" name="website" autocomplete="off" placeholder="www.yoursite.com">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Yourid@gmail.com" required>

    <label for="subject">Subject:</label>
    <input type="text" id="subject" name="subject" placeholder="The title of your message" required>

    <label for="message">Message:</label>
    <textarea rows="3" id="message" name="message" placeholder="Type your message here" required></textarea>

    <input id="mybtn" type="submit" value="SEND MESSAGE">
    <span id="status"> </span>
    <input type="hidden" id="formid" name="formid" value="1001">

</form>

<script>
    const contactForm = document.querySelector("form#contactForm");
    const statusSpan = document.querySelector("span#status");
    const submitBtn = document.querySelector("input#mybtn");

    function submitCform() {
        document.querySelector("form#contactForm #mybtn").disabled = "true";
        document.querySelector("form#contactForm #mybtn").value = 'Please wait...';

        var formdata = new FormData(contactForm);
        formdata.append('action', 'submitmyform');
        AjaxCform(formdata);
    }

    async function AjaxCform(formdata) {
        const ajaxurl = location.protocol + '//' + window.location.hostname + '/wp-admin/admin-ajax.php';

        try {
            const response = await fetch(ajaxurl, {
                method: 'POST',
                body: formdata,
            });

            const data = await response.json();
            console.log(data);

            if (data.statuse == 'ok') {
                contactForm.innerHTML = `<div id="success">${data.reply}</div>`;
            } else {
                statusSpan.innerHTML = `<div id="er">${data.reply}</div>`;
                submitBtn.disabled = false;
                submitBtn.value = 'Try again';
            }
        } catch (error) {
            statusSpan.innerHTML = `<div id="er">Network error. Please try again.</div>`;
            submitBtn.disabled = false;
            submitBtn.value = 'Try again';
        }
    }
</script>

<style>
    #website {display: none;}

    form#contactForm {
        max-width: 95%;
        width: 550px;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 15px 29px -22px #afa9a9;
        background-color: white;
    }

    #er {
        color: #f21515;
        background-color: #fff0f0;
        font-weight: bold;
        border: 3px solid #f21515;
        border-radius: 5px;
        padding: 10px 10px;
        max-width: fit-content;
        margin: 15px auto;
    }

    #success {
        color: #23a238;
        background-color: azure;
        font-weight: bold;
        border: 3px solid #23a238;
        border-radius: 5px;
        padding: 10px 10px;
        max-width: fit-content;
        margin: 15px auto;
    }

    label {
        display: block;
        padding-top: 15px;
        margin-left: 10px;
    }

    #contactForm input::placeholder, #contactForm textarea::placeholder {
        color: #b5b5b5;
    }

    #contactForm input[placeholder], #contactForm textarea[placeholder] {
        font-size: 1rem;
        line-height: 2rem;
    }

    form#contactForm input#mybtn {
        margin-top: 15px;
        display: block;
        height: 3rem;
        color: white;
        background: #0088a7;
        padding: 0px 23px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    form#contactForm input[type="text"], form#contactForm input[type="email"], form#contactForm textarea {
        width: 100%;
        border: 1px solid #f1f0f0;
        border-radius: 5px;
        padding: 3px 10px;
        margin-top: 3px;
    }
</style>
```

### Customizing the Form HTML (Optional)

#### Modify Form Appearance
The CSS inside the `<style>` tags is optional. You can:
- Delete the entire `<style>` block and use your theme's styles.
- Customize the CSS to match your site's branding.

#### Add or Remove Form Fields
- **To remove a field**, delete its corresponding `<label>` and `<input>` block from the HTML (e.g., remove the "Subject" field).
- **To add a field**, insert a new block following this pattern:
  ```html
  <label for="Phone">Phone:</label>
  <input type="text" id="Phone" name="Phone" placeholder="0930xxxxxx" required>
  ```
- **Important:** Do **NOT** delete the hidden honeypot field (with `id="website"`). It is essential for blocking spam bots.

---

## Step 3: Test Your Contact Form

1. Visit the page where you added the form on your live website.
2. Fill in the required fields (leave the hidden honeypot field empty) and submit the form.
3. You should see a **success message**.
4. Check the email inbox (site admin email or the one you configured) to confirm the form submission was received.

---

## Troubleshooting and Common Issues

### "Sorry, There is a problem in sending your form"

This error typically means the `wp_mail()` function failed. The most common cause is that PHP mail is disabled on your server. Solutions:
- **Enable SMTP:** You can manually configure SMTP or use the **WP Mail SMTP** plugin.
- Follow a tutorial on [configuring SMTP manually](link-to-your-smtp-tutorial).

### Can I integrate reCAPTCHA with this form?

This specific code includes a **honeypot** (the hidden `website` field), which blocks up to 90% of spam effectively. Adding Google reCAPTCHA is not supported in this code structure due to the AJAX processing, but the honeypot provides robust protection.

### How to Save Form Data to the Database?

The current code only sends an email. To save submissions to the database, you would need to add custom functionality that stores the sanitized `$_POST` data. This would be an advanced custom feature.

---

## Update: Add "Reply-To" Support

To allow you to reply directly to the person who submitted the form, the code already includes a `Reply-To` header. When you reply to the email notification, it will be sent to the user's provided email address.

```php
$headersmyform .= "Reply-To: $email\r\n";
```
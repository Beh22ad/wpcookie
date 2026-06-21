# How To Redirect Pages In WordPress Without A Plugin

This tutorial teaches you two ways to redirect links in WordPress without using any plugin.

---

## Introduction

- **First method (Hardcoded URL):** Easier to set up. Suitable for a small number of links.
- **Second method (Dynamic with Custom Fields):** Requires more setup. Ideal if you have many links for redirection.

### Step 1: Get the From (Old) and To (New) URL Slug

The **"slug"** is the part of the URL without the domain name.
*Example:* For the URL `https://yoursite.com/product-category/blue-shirt/`, the slug is `/product-category/blue-shirt/`.

**Example for this tutorial:**
- Redirect from: `https://redpishi.com/old-product-category/old-blue-shirt/`
- Redirect to: `https://redpishi.com/new-blue-shirt/`

Slugs to use:
- `from`: `/old-product-category/old-blue-shirt/`
- `to`: `/new-blue-shirt/`

---

## First Method: Hardcoded URL

### Step 2: Add Custom Code in functions.php

Copy the code below, change `[from slug]` and `[to slug]` to your own slugs, and paste it into your **child theme's `functions.php`** file.

> **Important:** If you don't have a child theme, [create one first](link-to-your-child-theme-guide).

```php
function redirect_page() {

    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $currenturl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $currenturl_relative = wp_make_link_relative($currenturl);

    switch ($currenturl_relative) {

        case '[from slug]':
            $urlto = home_url('[to slug]' );
            break;

        default:
            return;
    }

    if ($currenturl != $urlto)
        exit( wp_redirect( $urlto ) );
}
add_action( 'template_redirect', 'redirect_page' );
```

**To add more redirects:** Copy the following portion for each additional link and paste it inside the `switch` block:

```php
case '[from slug2]':
    $urlto = home_url('[to slug2]' );
    break;
```

---

## Second Method: Build a Simple Dashboard with Custom Fields

### Step 2: Add Custom Code in functions.php

Remove any previous redirect code from your child theme's `functions.php` and paste the code below:

```php
/* redirect by redpishi.com */ 
function redpishi_com_redirect() {
    $redirectArr = array();
    $id = get_page_by_title( 'redirect' )->ID;
    $rowArr = get_post_meta($id);	
    foreach ($rowArr as $key => $value) {	
        if (strpos($key, "/") === 0) {
            $redirectArr[trim($key)] = trim($value[0]);
        }			
    }

    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $currenturl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $currenturl_relative = wp_make_link_relative($currenturl);

    if (substr($currenturl_relative, -1) == '/') { 
        $currenturl_relative = substr_replace($currenturl_relative, "", -1);
    }

    $forceredirect = 0;
    foreach($redirectArr as $from => $to) {
        if (substr($from, -1) == '/') { 
            $from = substr_replace($from, "", -1);
        }
        if( $currenturl_relative == $from) {
            $forceredirect = 1;
            $urlto = home_url($to);
        }
    }

    if ($forceredirect == 1) {
        wp_redirect( $urlto);
        exit;		
    }
}
add_action( 'template_redirect', 'redpishi_com_redirect' );
```

### Step 3: Build a Page and Activate Custom Fields

1. **Create a new page** in WordPress and name it exactly **"redirect"**.
2. **Enable Custom Fields:**
   - Click the 3 dots at the top right of the screen and select **Preferences**.
   - Go to the **Panels** tab and make sure the **Custom fields** option is ON.
3. **Change Visibility** to **Private** (for security reasons) and publish the page.

### Step 4: Enter and Save Your Links in Custom Fields

1. In the **Custom Fields** meta box on the "redirect" page, create a new field for each redirect.
2. **Name field:** Enter the "from slug" (e.g., `/old-product-category/old-blue-shirt/`).
3. **Value field:** Enter the "to slug" (e.g., `/new-blue-shirt/`).
4. Click **Update** to save.

You can add as many Custom Fields as you like.

---

## Frequently Asked Questions

### Are there any SEO implications when using this redirection method?

The redirection method described uses built-in WordPress functionality and is considered **SEO-friendly**. When implemented correctly, it performs a **301 redirect**, which:
- Informs search engines that the original page has permanently moved.
- Transfers SEO value and link equity to the new page.
- Ensures existing backlinks to the old page contribute to the new page's ranking.

> **Tip:** Update your internal links to the new page for better user experience and to reduce unnecessary redirects.

### Can I redirect multiple pages at once using this method?

- The **first method** redirects one page at a time but you can add multiple `case` statements for additional redirects.
- The **second method** allows you to manage multiple redirects easily through the WordPress dashboard.

For bulk management, you could also consider using a plugin or editing your `.htaccess` file, but these approaches require more technical knowledge.

### How can I test if the redirect is working correctly?

1. **Clear your browser cache** to avoid viewing a cached version.
2. Open a new **private or incognito** browser window.
3. Enter the URL of the original page (the "from" slug).
4. If it works, you'll be automatically redirected to the new page.
5. To confirm it's a **301 redirect**, use an online tool like **Redirect Checker** or an SEO browser extension (e.g., Ayima Redirect Path).
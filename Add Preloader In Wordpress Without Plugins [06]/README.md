# How to Add a Preloader in WordPress (Without Plugins)

This tutorial teaches you how to add a custom preloader animation to your WordPress site without using any plugin. Preloaders (also known as loaders) appear on the screen while the rest of the page's content is still loading, keeping visitors engaged during server operations.

---

## Step 1: Find an Animated Loader Icon

1. Search for "loader animation" online to find hundreds of free resources.
2. **Recommended format:** Use **SVG** files (they are smaller and load faster). GIF files are a good alternative.
3. You can use the three SVG animations provided in this repository (named `preloader1.svg`, `preloader2.svg`, and `preloader3.svg`).
4. Upload your chosen animation file to your site (e.g., in your child theme folder or media library) and copy its URL for the next step.

---

## Step 2: Copy the Snippet Code into functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the code below and paste it into the file.
4. **Customize the variables** (explained after the code) and save the file.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
// WordPress Preloader by Redpishi.com

add_action( 'init', 'Redpishi_Preloader' );
function Redpishi_Preloader() { 
    if(!is_admin() &&  $GLOBALS["pagenow"] !== "wp-login.php" ) { 

        $delay = 1;	// seconds
        $loader = 'https://redpishi.com/wp-content/uploads/2022/06/preloader3.svg'; // <-- REPLACE WITH YOUR SVG URL
        $overlayColor = '#ffffff'; // <-- REPLACE WITH YOUR OVERLAY COLOR

        echo '<div class="Preloader"><img src="'.$loader.'" alt="" style="height: 150px;"></div>
        <style>
        .Preloader {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: '.$overlayColor.';
            z-index: 100000;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        </style>
        <script>
        document.body.style.overflow = "hidden";
        document.addEventListener("DOMContentLoaded", () => setTimeout( function() { document.querySelector("div.Preloader").remove(); document.body.style.overflow = "visible"; } , '.$delay.' * 1000));
        </script>
    '; 
    }
}
```

---

## Customization Options

You can adjust the following variables in the code to customize the preloader behavior:

| Variable | Description | Example |
| :--- | :--- | :--- |
| **`$delay`** | Number of seconds the loader is displayed. Set to `0` to hide it as soon as the page is fully loaded. | `1` (1 second) |
| **`$loader`** | The full URL to your preloader animation file (SVG, GIF, etc.). | `'https://yoursite.com/wp-content/uploads/preloader1.svg'` |
| **`$overlayColor`** | The background color of the preloader overlay (hex code or `transparent`). | `'#ffffff'` (white) or `'transparent'` |

### Using Local SVG Files

If you have placed the SVG files in the same directory as your README (or in your Git repository), you would need to upload them to your WordPress site first. After uploading, replace the `$loader` variable with the actual URL of the uploaded file.

---

## Testing

After saving the `functions.php` file, visit your website. The preloader should appear briefly while the page loads.

---

## Frequently Asked Questions

### Will adding a preloader slow down my website's loading time?

Adding a preloader should not significantly impact your loading time, as it is a lightweight feature. However, ensure your preloader file is optimized (SVG is ideal) and test your site's performance after implementation.

### Can I use a custom preloader design or animation?

Yes, you can use any SVG animation, image, or custom HTML/CSS as your preloader. Simply update the `$loader` variable to point to your custom file or replace the `<img>` tag with your custom code.

### Is it possible to display the preloader only on specific pages or posts?

Yes. To do this, you would need to modify the conditional logic in the code. Instead of using `if(!is_admin()...`, you can use WordPress conditional tags like `is_page()`, `is_single()`, or `is_front_page()` to target specific content.

### Notes on Common Issues

- **CSS/JS conflict:** If the preloader causes layout issues after disappearing, check your theme's CSS for conflicts with the `overflow: hidden` style.
- **Not working on certain pages:** Ensure the preloader code is correctly added to the `functions.php` and that the `init` hook is not being overridden by other plugins or themes.
```

You can copy this entire block and paste it directly into your `README.md` file. The code now includes a note about using local SVG files, but remember that for a WordPress site, these files must be uploaded to the server and referenced by their URL.
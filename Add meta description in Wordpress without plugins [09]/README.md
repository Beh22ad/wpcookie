# How to Add Meta Description in WordPress (Without Plugins)

This tutorial teaches you how to manually add meta descriptions to your WordPress pages and posts without using any SEO plugin. Meta descriptions are important for SEO as they summarize the page content and appear in search engine results (SERPs).

---

## Introduction

By default, WordPress correctly adds the **title tag** to all pages. However, it does not automatically add a **meta description**. You need to implement it yourself to control what appears in search results.

**Example of a meta description in HTML:**
```html
<meta name="description" content="Your description 🥰">
```

---

## Step 1: Add Code Snippet to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the following code and paste it into the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
function meta_description_r(){
    if( is_single() || is_page() ) { ?>
        <meta name="description" content="<?= wp_strip_all_tags( get_the_excerpt(), true ); ?>">
    <?php }
}
add_action('wp_head', 'meta_description_r');
```

### How This Code Works

- **`wp_head` hook:** Inserts the meta description into the `<head>` section of your site.
- **`is_single() || is_page()`:** Targets only single posts and pages (not archive pages, categories, etc.).
- **`get_the_excerpt()`:** Retrieves the post's excerpt.
- **`wp_strip_all_tags()`:** Removes any HTML tags from the excerpt, ensuring a clean meta description.

---

## Step 2: Write Excerpts for Your Articles

For the meta description to be accurate, you should write a custom excerpt for each post or page:

1. Go to **Posts → All Posts** (or **Pages → All Pages**) in your WordPress dashboard.
2. Click to edit a post or page.
3. Look for the **Excerpt** box below the main editor (if not visible, enable it via **Screen Options** at the top).
4. Enter a concise, compelling summary of the content (optimally 150-160 characters).
5. Update/publish the page.

**Important:** If an excerpt is empty, this code will automatically use the **first 600 characters** of the post content as the meta description.

---

## Testing

To verify the meta description is working:

1. View a single post or page on your website.
2. Right-click on the page and select **View Page Source** (or similar option).
3. Look for the `<meta name="description"` tag in the `<head>` section.
4. Confirm it matches the excerpt you wrote (or the first 600 characters of the content).

---

## Frequently Asked Questions

### Can I use this code instead of an SEO plugin?

**Yes.** For basic SEO meta description functionality, this code works perfectly. However, full SEO plugins offer additional features like:
- XML sitemaps
- Social media tags (Open Graph)
- Schema markup
- Advanced meta title control

If you only need meta descriptions, this solution is lightweight and efficient.

---

## Important Notes

- **Character Limit:** Search engines typically display **150-160 characters** of a meta description. Aim for this length.
- **Homepage:** This code does not add a meta description to the homepage. You would need to add a separate condition for `is_front_page()`.
- **Archive Pages:** Category, tag, and other archive pages are not covered by this snippet.

---

## Code Customization (Optional)

To add a meta description to your **homepage**, modify the condition:

```php
function meta_description_r(){
    if( is_front_page() || is_single() || is_page() ) { ?>
        <meta name="description" content="<?= wp_strip_all_tags( get_the_excerpt(), true ); ?>">
    <?php }
}
add_action('wp_head', 'meta_description_r');
```

> **Note:** For the homepage, you would need to set an excerpt for the page or specify a custom description in your code.
# Canonical URLs in WordPress: A Complete Guide

As your website grows, chances are you have several pages or posts where the content is nearly the same. This can cause duplicate content issues where a search engine like Google doesn't know which of the URLs to include in the search results.

A canonical tag (aka "rel canonical") is a way of telling search engines that a specific URL represents the master copy of a page. Using the canonical tag prevents problems caused by identical or "duplicate" content appearing on multiple URLs. Practically speaking, the canonical tag tells search engines which version of a URL you want to appear in search results.

---

## Table of Contents

- [What is Canonical URLs](#what-is-canonical-urls)
- [Setting Canonical URLs in Yoast SEO](#setting-canonical-urls-in-yoast-seo)
- [Setting Canonical URLs in Rank Math](#setting-canonical-urls-in-rank-math)
- [Add Canonical Tag in WordPress Without Plugins](#add-canonical-tag-in-wordpress-without-plugins)

---

## What is Canonical URLs

---

## Setting Canonical URLs in Yoast SEO

### Step 1: Log in to your WordPress website

You are now in your 'Dashboard'.

### Step 2: Go to the post, page, or taxonomy for which you want to change the canonical URL

On the left-hand side, you will see a menu that allows you to navigate to your posts, pages, and other content. Find the content you want to edit, and click on it to access the editing screen.

### Step 3: Once you are in the editing screen, go to the 'Advanced' section in the Yoast SEO sidebar or meta box

**Note:** The Advanced section in the meta box is part of the SEO tab.

### Step 4: Enter the full canonical URL

Include http/s and www or non-www, in the 'Canonical URL' field.

### Step 5: Update the post, page, or taxonomy

---

## Setting Canonical URLs in Rank Math

### Step 1: Edit the Post/Page

At first, you need to open the post/page that contains the duplicate content by clicking on the Edit as shown below:

### Step 2: Navigate to the Advanced Tab of Rank Math

Once you've opened the post/page, navigate to the Advanced tab of the Rank Math in your editor. If you're unable to find the Advanced tab, please enable the advanced mode from **WordPress Dashboard > Rank Math > Dashboard**.

### Step 3: Change the Canonical URL

Under the Advanced tab, you can change the Canonical URL field to point to the main source of your content. The Canonical URL informs the search crawlers of the main page if you have pages or posts with similar content.

### Step 4: Save the Post

Once you're done setting your Canonical URL – simply update the page as you normally would after making a change or click Publish if this is a newly created page.

---

## Add Canonical Tag in WordPress Without Plugins

### Step 1: Add the Following Code to the functions.php File

Inside the WordPress dashboard Theme Editor or on the web server, find and open the `functions.php` file. Go to **Appearance -> Theme Editor**.

**Important:** You must create a child theme before making any changes to `functions.php` file. Otherwise, the applied changes will be lost after each update.

As an alternative method, you can use the Code Snippets plugin to insert your codes into WordPress.

Copy the PHP code from the below box and paste it at the end of the `functions.php` file:

```php
function canonical_r(){
    if( !is_admin() ) {
        $canonical_raw = (get_post_meta(get_the_ID(), 'canonical', true));
        if ($canonical_raw) {
            $canonical_url = preg_replace('/https*:\/\/|\s/', '', $canonical_raw);
            ?>
            <link rel="canonical" href="<?php echo 'https://'.$canonical_url; ?>" />
            <?php
        }
    }
}
add_action('wp_head', 'canonical_r');
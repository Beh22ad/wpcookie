# How to Add Keywords in WordPress (Without Plugins)

This tutorial teaches you how to add meta keywords to your WordPress posts and pages manually, without using any SEO plugin. Meta keywords are a type of meta tag that helps indicate the topic of a page to search engines.

---

## Introduction

Meta keywords appear in the HTML code of a page like this:

```html
<head>
    <meta name="keywords" content="meta keywords html, meta tag generator, meta tags example, meta tag attributes, meta tag in html">
</head>
```

> **Important:** While meta keywords were historically important for SEO, most major search engines (like Google) no longer use them for ranking. However, they can be useful for internal search engines or other systems that still read them.

WordPress does not have built-in support for adding meta keywords, which is why you might need a plugin. This tutorial provides a manual, plugin-free solution.

---

## Step 1: Add the Code to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the following code and paste it at the end of the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
add_action('wp_head', function (){
    if( is_single() || is_page() ) {
        $meta_keywords_raw = (get_post_meta(get_the_ID(), 'keywords', true));
        if ($meta_keywords_raw) {
            $meta_keywords = preg_replace('/\s*,\s*/', ', ', $meta_keywords_raw);
            ?>
            <meta name="keywords" content="<?= $meta_keywords; ?>">
            <?php
        }
    }
});
```

### How This Code Works

- **`wp_head` hook:** Inserts the meta tag into the `<head>` section of your site.
- **`is_single() || is_page()`:** Targets only single posts and pages (not archive pages, categories, etc.).
- **`get_post_meta()`:** Retrieves a custom field value named `keywords` for the current post/page.
- **`preg_replace()`:** Cleans up the spacing in your keyword list (ensures consistent formatting).
- **Output:** If a "keywords" custom field exists, it outputs the `<meta name="keywords">` tag with your keywords.

---

## Step 2: Enable Custom Fields on Your Post/Page

Before you can add keywords, you need to enable the Custom Fields panel in the editor:

1. Go to **Posts → All Posts** (or **Pages → All Pages**) and edit a post/page.
2. At the top right of the editor, click the **three dots** (options menu).
3. Select **Preferences** from the menu.
4. In the pop-up window, go to the **Panels** tab.
5. Under the "Additional" section, toggle **Custom Fields** to the **ON** position.
6. Click **Enable and Reload** when prompted. The page will reload.

After reloading, you'll see a **Custom Fields** section below the editor.

---

## Step 3: Create a Custom Field for Meta Keywords

In the Custom Fields section:

1. Under the "Name" column, click **Enter New** (below the "- Select -" dropdown).
2. Type **`keywords`** in the name field (this exact name is required by the code).
3. In the "Value" field, type your keywords **separated by commas**.
   - Example: `wordpress tips, meta keywords, seo basics, custom fields`
4. Click the **Add Custom Field** button to save.

---

## Step 4: Update and Verify

1. **Update/Publish** your post or page.
2. View the published page on your site.
3. **View the page source** (right-click and select "View Page Source" or press `Ctrl+U`).
4. Look for the `<meta name="keywords"` tag in the `<head>` section.

**Example output in source:**
```html
<meta name="keywords" content="wordpress tips, meta keywords, seo basics, custom fields">
```

---

## Best Practices for Meta Keywords

- **Relevance:** Ensure every keyword accurately reflects the page content.
- **Natural usage:** Use keywords naturally within your content.
- **Avoid stuffing:** Don't use excessive or irrelevant keywords.
- **Unique keywords:** Try to use unique keywords per page to avoid competition.

---

## Frequently Asked Questions

### How many keywords should I add to my posts?

There is no fixed number. Instead of focusing on quantity:
- Use a few **primary keywords** and their variations (synonyms, long-tail phrases).
- Prioritize **quality and relevance** over quantity.
- Avoid **keyword stuffing** (it can hurt user experience and SEO).

### Can I use the same keywords for multiple posts?

While possible, it's generally **not recommended**. Using the same keywords across different pages can lead to **keyword cannibalization**, where search engines struggle to determine which page is most relevant. Instead, use unique or varied keywords for each post.

### Is it necessary to add keywords to every post and page?

**No.** It's not essential for every page. Focus on:
- Adding keywords where they naturally fit.
- Prioritizing high-quality, valuable content for your audience.
- Only using keywords when they enhance the user experience.

### How long do keywords take to show up in SEO tools?

It can take **a few weeks** for newly added keywords to appear in tools like Moz or SEMrush. This is because:
1. Search engines need to **crawl and index** your site.
2. The tools then need to update their databases (which happens periodically).

---

## Important Note on SEO

> **Google and other major search engines do not use the `keywords` meta tag for ranking.** This code is primarily useful for other systems (e.g., internal site search, some third-party tools) that still rely on meta keywords. For actual search engine optimization, focus on **content quality, user experience, and proper use of title tags and meta descriptions**.
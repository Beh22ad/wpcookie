# How to Add Live Ajax Search in WordPress (Without Plugins)

This tutorial teaches you how to add a live, AJAX-powered search bar to your WordPress site without using any plugin. This is particularly useful for e-commerce sites, helping users quickly find products and improve conversion rates.

---

## Introduction

WordPress does not include live AJAX search by default. This solution uses a customizable shortcode to create a search bar that fetches results dynamically as the user types. It can search in **posts, pages, products, and custom post types**.

> **Important:** If you are using a shared web host, be aware that AJAX search may increase server load because a new request is sent to the server for every character typed.

---

## Step 1: Copy the AJAX Search Code to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the full code below and paste it into the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
/*
 * Ajax search by redpishi.com
 * https://redpishi.com/wordpress-tutorials/live-ajax-search-in-wordpress/
 */
add_shortcode( 'asearch', 'asearch_func' );
function asearch_func( $atts ) {
    $atts = shortcode_atts( array(
        'source' => 'page,post,product',
        'image' => 'true'
    ), $atts, 'asearch' );

    static $asearch_first_call = 1;
    $source = $atts["source"];
    $image = $atts["image"];

    $sForam = '<div class="search_bar">
        <form class="asearch" id="asearch'.$asearch_first_call.'" action="/" method="get" autocomplete="off">
            <input type="text" name="s" placeholder="Search ..." id="keyword" class="input_search" onkeyup="searchFetch(this)"><button id="mybtn">🔍</button>
        </form>
        <div class="search_result" id="datafetch" style="display: none;">
            <ul>
                <li>Please wait..</li>
            </ul>
        </div>
    </div>';

    $java = '<script>
    function searchFetch(e) {
        var datafetch = e.parentElement.nextSibling;
        if (e.value.trim().length > 0) { 
            datafetch.style.display = "block"; 
        } else { 
            datafetch.style.display = "none"; 
        }
        const searchForm = e.parentElement;	
        e.nextSibling.value = "Please wait...";
        var formdata'.$asearch_first_call.' = new FormData(searchForm);
        formdata'.$asearch_first_call.'.append("source", "'.$source.'"); 
        formdata'.$asearch_first_call.'.append("image", "'.$image.'"); 
        formdata'.$asearch_first_call.'.append("action", "asearch"); 
        AjaxAsearch(formdata'.$asearch_first_call.', e); 
    }

    async function AjaxAsearch(formdata, e) {
        const url = "'.admin_url("admin-ajax.php").'?action=asearch";
        const response = await fetch(url, {
            method: "POST",
            body: formdata,
        });
        const data = await response.text();
        if (data) {
            e.parentElement.nextSibling.innerHTML = data;
        } else {
            e.parentElement.nextSibling.innerHTML = `<ul><a href="#"><li>Sorry, nothing found</li></a></ul>`;
        }
    }	

    document.addEventListener("click", function(e) {
        if (document.activeElement.classList.contains("input_search") == false) {
            [...document.querySelectorAll("div.search_result")].forEach(e => e.style.display = "none");
        } else {
            if (e.target.value.trim().length > 0) {
                e.target.parentElement.nextSibling.style.display = "block";
            }
        }
    });
    </script>';

    $css = '<style>
    form.asearch {
        display: flex;
        flex-wrap: nowrap;
        border: 1px solid #d6d6d6;
        border-radius: 5px;
        padding: 3px 5px;
    }
    form.asearch button#mybtn {
        padding: 5px;
        cursor: pointer;
        background: none;
    }
    form.asearch input#keyword {
        border: none;
    }
    div#datafetch {
        background: white;
        z-index: 10;
        position: absolute;
        max-height: 425px;
        overflow: auto;
        box-shadow: 0px 15px 15px #00000036;
        right: 0;
        left: 0;
        top: 50px;
    }
    div.search_bar {
        width: 600px!important;
        max-width: 90%!important;
        position: relative;
    }
    div.search_result ul a li {
        margin: 0px;
        padding: 5px 0px;
        padding-inline-start: 18px;
        color: #3f3f3f;
        font-weight: bold;
    }
    div.search_result li {
        margin-inline-start: 20px;
    }
    div.search_result ul {
        padding: 13px 0px 0px 0px;
        list-style: none;
        margin: auto;
    }
    div.search_result ul a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    div.search_result ul a:hover {
        background-color: #f3f3f3;
    }
    .asearch input#keyword {
        width: 100%;
    }
    </style>';

    if ( $asearch_first_call == 1 ) {	
        $asearch_first_call++;
        return "{$sForam}{$java}{$css}"; 
    } elseif ( $asearch_first_call > 1 ) {
        $asearch_first_call++;
        return "{$sForam}"; 
    }
}

add_action('wp_ajax_asearch' , 'asearch');
add_action('wp_ajax_nopriv_asearch','asearch');

function asearch(){
    $the_query = new WP_Query( array( 
        'posts_per_page' => 10, 
        's' => esc_attr( $_POST['s'] ), 
        'post_type' =>  explode(",", esc_attr( $_POST['source'] ))
    ));

    if( $the_query->have_posts() ) :
        echo '<ul>';
        while( $the_query->have_posts() ): $the_query->the_post(); ?>
            <a href="<?php echo esc_url( post_permalink() ); ?>">
                <li><?php the_title();?></li>
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'single-post-thumbnail' ); ?>                               
                <?php if ( $image[0] && trim(esc_attr( $_POST['image'] )) == "true" ) { ?>
                    <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" style="height: 60px;padding: 0px 5px;">
                <?php } ?>
            </a>
        <?php endwhile;
        echo '</ul>';
        wp_reset_postdata();  
    endif;
    die();
}
```

This code defines a shortcode named `asearch`. The next step explains how to use it.

---

## Step 2: Use the "asearch" Shortcode

After adding the code to `functions.php`, you can place the `asearch` shortcode on any page, post, or widget area.

### Basic Usage

```
[asearch]
```

### Available Attributes

| Attribute | Description | Default | Example |
| :--- | :--- | :--- | :--- |
| **`image`** | Show or hide featured images/thumbnails in results. | `true` | `image="false"` |
| **`source`** | Comma-separated list of post types to search. | `page,post,product` | `source="product"` |

### Examples

```php
// Search in all default post types (pages, posts, products)
[asearch]

// Search only in products
[asearch source="product"]

// Search only in posts and pages (no products)
[asearch source="post, page"]

// Search in a custom post type (e.g., "movies")
[asearch source="movies"]

// Search with no images displayed
[asearch image="false"]

// Search in custom post type with no images
[asearch source="movies" image="false"]
```

---

## Adding the Search Form to a Theme File (e.g., header.php)

If you want to add the search bar directly to your theme template (e.g., `header.php`) instead of using a shortcode:

1. Open your theme's `header.php` file (preferably in a child theme).
2. Add this PHP code where you want the search bar to appear:

```php
<?php echo do_shortcode('[asearch]'); ?>
```

> **Note:** You can add attributes to the shortcode in the `do_shortcode()` function as well, e.g.:
> ```php
> <?php echo do_shortcode('[asearch source="product" image="false"]'); ?>
> ```

---

## Customization Tips

### Styling the Search Results

You can modify the CSS in the code (the part between `<style>` tags) to match your theme's design. Key selectors:
- `.search_bar` – Main container
- `.asearch` – Form element
- `.search_result` – Results dropdown
- `.search_result ul a` – Each result item

### Limiting the Number of Results

To change the maximum number of results displayed, find this line in the code:
```php
'posts_per_page' => 10,
```
Change `10` to your desired number.

### Adding Product SKU or Price to Results

For e-commerce sites, you can add SKU or price by modifying the `while` loop in the `asearch()` function. For example:

```php
// Inside the while loop, after the title
$product_id = get_the_ID();
$product = wc_get_product($product_id);
if ($product) {
    echo '<span class="product-price">' . $product->get_price() . ' $</span>';
}
```

### Searching in a Specific Category

To limit search results to a specific category ID, modify the `WP_Query` arguments:

```php
$the_query = new WP_Query( array( 
    'posts_per_page' => 10, 
    's' => esc_attr( $_POST['s'] ), 
    'post_type' =>  explode(",", esc_attr( $_POST['source'] )),
    'cat' => 7 // Replace with your category ID
));
```

---

## Frequently Asked Questions

### Can I display additional information (like categories or tags) in search results?

Yes. In the `asearch()` function, inside the `while` loop, you can use WordPress functions like `get_the_category()`, `get_the_tags()`, or `get_post_meta()` to retrieve extra data and include it in the output.

### The search works when I'm logged in but not for guests (logged out). Why?

The code includes both `wp_ajax` and `wp_ajax_nopriv` hooks, which should work for all users. If it's not working for guests, a security plugin might be blocking AJAX requests from non-authenticated users. Try temporarily disabling any security or caching plugins.

### How can I add a "View All" or "See More" link at the bottom of search results?

You would need to modify the PHP code to check the total number of matching posts and conditionally add a link to the main search results page. This requires custom coding beyond the basic snippet.

### Can I use this search with custom post types and taxonomies?

Yes. Add your custom post type name to the `source` attribute, e.g., `[asearch source="movies"]`. For taxonomies, you would need additional custom code to modify the `WP_Query` parameters.

### Why is the search slow on my site?

AJAX search sends a request per keystroke. On shared hosting with many concurrent users, this can increase server load. Consider adding a **debounce** to the JavaScript (delaying the request until the user pauses typing) or using a dedicated search solution.
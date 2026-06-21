# How to Duplicate a Page in WordPress (Without Plugins)

This tutorial teaches you how to clone a page or post in WordPress, including all associated data (content, meta fields, taxonomies, etc.), without using any plugin. You'll learn two methods: a manual copy-paste approach and a code-based "Duplicate" button.

---

## Introduction

There are many scenarios where you need to duplicate a page or post. By default, WordPress does not have this functionality. This guide provides two solutions to copy pages, posts, and all their data efficiently.

---

## Method 1: Duplicate a Page Manually (No Code)

This method is simple and quick for occasional duplication.

### Steps:

1. In your WordPress dashboard, go to **Posts** or **Pages**.
2. **Hover** over the post/page you want to copy to reveal the action links.
3. Click the **ellipses menu** (three vertical dots) and select **"Copy all content"** (or use the "Copy" option in the block editor).
4. Create a **New Post** or **New Page**.
5. **Paste** the clipboard content into the new page/post.
6. The new page/post will now be a clone of the original. Edit and publish as needed.

---

## Method 2: Duplicate Using a Code Snippet (Adds a "Duplicate" Button)

This method adds a **"Duplicate" link** to the list of posts/pages. When clicked, it instantly creates a draft copy of the selected item. This is ideal if you frequently duplicate content.

### Step 1: Add the Code to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the full code below and paste it at the end of the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
add_filter( 'page_row_actions', 'rd_duplicate_post_link', 10, 2 );
function rd_duplicate_post_link( $actions, $post ) {

	if( ! current_user_can( 'edit_posts' ) ) {
		return $actions;
	}

	$url = wp_nonce_url(
		add_query_arg(
			array(
				'action' => 'rd_duplicate_post_as_draft',
				'post' => $post->ID,
			),
			'admin.php'
		),
		basename(__FILE__),
		'duplicate_nonce'
	);

	$actions[ 'duplicate' ] = '<a href="' . $url . '" title="Duplicate this item" rel="permalink">Duplicate</a>';

	return $actions;
}

add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

function rd_duplicate_post_as_draft(){

	// Check if post ID has been provided
	if ( empty( $_GET[ 'post' ] ) ) {
		wp_die( 'No post to duplicate has been provided!' );
	}

	// Nonce verification for security
	if ( ! isset( $_GET[ 'duplicate_nonce' ] ) || ! wp_verify_nonce( $_GET[ 'duplicate_nonce' ], basename( __FILE__ ) ) ) {
		return;
	}

	// Get the original post id
	$post_id = absint( $_GET[ 'post' ] );

	// Get all original post data
	$post = get_post( $post_id );

	// Set the new post author (current user)
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;

	if ( $post ) {

		// New post data array
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft', // Set to 'draft' to avoid publishing accidentally
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		// Insert the new post (draft)
		$new_post_id = wp_insert_post( $args );

		// Copy taxonomies (categories, tags, etc.)
		$taxonomies = get_object_taxonomies( get_post_type( $post ) );
		if( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}
		}

		// Copy all post meta data (custom fields, etc.)
		$post_meta = get_post_meta( $post_id );
		if( $post_meta ) {
			foreach ( $post_meta as $meta_key => $meta_values ) {
				if( '_wp_old_slug' == $meta_key ) {
					continue; // Skip the old slug meta
				}
				foreach ( $meta_values as $meta_value ) {
					add_post_meta( $new_post_id, $meta_key, $meta_value );
				}
			}
		}

		// Redirect to the list of posts/pages with a success message
		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type' => ( 'post' !== get_post_type( $post ) ? get_post_type( $post ) : false ),
					'saved' => 'post_duplication_created'
				),
				admin_url( 'edit.php' )
			)
		);
		exit;

	} else {
		wp_die( 'Post creation failed, could not find original post.' );
	}
}

// Show a success notice after duplication
add_action( 'admin_notices', 'rudr_duplication_admin_notice' );

function rudr_duplication_admin_notice() {
	$screen = get_current_screen();

	if ( 'edit' !== $screen->base ) {
		return;
	}

	if ( isset( $_GET[ 'saved' ] ) && 'post_duplication_created' == $_GET[ 'saved' ] ) {
		echo '<div class="notice notice-success is-dismissible"><p>Post copy created.</p></div>';
	}
}
```

> **Code Credit:** This code is based on the original work from [rudrastyh.com](https://rudrastyh.com/).

### Step 2: Use the New "Duplicate" Button

1. After saving the code, navigate to **Posts** or **Pages** in your dashboard.
2. Hover over any post or page in the list.
3. You will now see a new **"Duplicate"** link.
4. Click **"Duplicate"**.
5. A new **draft** copy of the page/post will be created (including its content, meta data, categories, tags, etc.).
6. You will be redirected to the list view with a **"Post copy created."** success notice.
7. Edit the new draft as needed (e.g., change the title, content, etc.) and publish it.

---

## Important Notes

- **Author:** The duplicate will be assigned to the **current logged-in user** (the person who clicks the Duplicate button).
- **Status:** The duplicate is created as a **draft** to prevent accidental publication.
- **Taxonomies:** All categories, tags, and custom taxonomies are copied over.
- **Meta Data:** All custom fields and post meta are copied (except the `_wp_old_slug` entry).
- **Permissions:** Only users with the `edit_posts` capability will see the Duplicate button.

---

## Frequently Asked Questions

### Does this method also duplicate featured images and other attachments?

The code above copies **post meta**, which includes the attachment IDs for featured images. Therefore, the featured image (and any other meta-linked attachments) will be associated with the new draft copy, using the same image files.

### Can I use this method for custom post types?

**Yes.** The code automatically works with any public post type. The "Duplicate" link will appear in the list view for all post types (including custom ones). The `post_type` parameter is handled dynamically.

### What if I want the duplicate to be published immediately?

Change the `'post_status'` parameter in the `$args` array from `'draft'` to `'publish'`. However, this is generally **not recommended** to avoid accidental duplication of live content.

### How do I change the author of the duplicated post?

The code sets the author to the current user. To set it to the original author instead, find this line:
```php
$new_post_author = $current_user->ID;
```
Replace it with:
```php
$new_post_author = $post->post_author;
```

### Can I use a plugin to do this instead?

Yes. There are many free plugins, such as **Yoast Duplicate Post** or **Duplicate Page**, that offer similar functionality with more features. However, this code solution is lightweight and does not add extra bloat to your site.
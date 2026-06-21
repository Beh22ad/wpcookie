# Create a Child Theme in WordPress Step by Step (Without Plugin)

This guide provides step-by-step instructions for creating a WordPress child theme manually, without using any plugins.

---

## Why We Need a Child Theme

Using a child theme allows you to modify or update your parent theme without losing your customizations. Without a child theme, you'd have to edit theme files directly—and any time you updated the theme, your customizations would be lost.

---

## How to Create a Child Theme in WordPress (Step by Step)

> **Note:** This tutorial uses **Blocksy** as the parent theme, but the steps are identical for **Astra** or any other WordPress theme.

### Step 1: Get the Text Domain of the Current Theme

1. In your WordPress dashboard, go to:  
   **Appearance → Theme File Editor**
2. Your active theme's `style.css` file should be selected by default. If not, select the main theme's Stylesheet file to display its content.
3. Look for the **`Text Domain`** in the file header.  
   Example: `Text Domain: blocksy`
4. Copy this text domain value (e.g., `blocksy`)—you'll need it in the next step.

---

### Step 2: Create Child Theme Files and Folder

1. **Create a new folder** on your computer and name it using this pattern:  
   `[parent-theme-text-domain]-child`  
   *Example:* `blocksy-child`

2. Inside this folder, create **two text files** and rename them exactly as:
   - `functions.php`
   - `style.css`

3. **Edit `functions.php`** — Open it in a text editor, paste the code below, and save:

   ```php
   <?php
   add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
   function enqueue_parent_styles() {
       wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   }
   ?>
   ```

4. **Edit `style.css`** — Open it in a text editor, paste the code below, and save:

   ```css
   /*
   Theme Name: My Custom Blocksy Theme    (Change to any name you like)
   Template: blocksy                      (Change to your parent theme's Text Domain)
   */
   ```

   > **Important:** Replace `Template: blocksy` with the actual Text Domain you copied in Step 1.

Your child theme is now ready!

---

### Step 3: Zip and Install the Child Theme on Your Site

1. Compress (zip) the child theme folder (e.g., `blocksy-child`) on your computer.
2. In your WordPress dashboard, go to:  
   **Appearance → Themes → Add New**
3. Click the **Upload Theme** button at the top.
4. Choose the zip file you created and click **Install Now**.
5. After successful installation, click **Activate** to enable your new child theme.

---

## Frequently Asked Questions

### Can I create a child theme for a theme not downloaded from the official WordPress repository?

Yes, you can create a child theme for any WordPress theme, regardless of its source. As long as the parent theme is properly coded and follows WordPress theme development standards, you can create a child theme by following the same steps outlined in this guide. Make sure to use the correct parent theme's name in the child theme's `style.css` file.

### Can I create multiple child themes for a single parent theme?

Yes, you can create multiple child themes for a single parent theme. Each child theme should have its own unique folder and `style.css` file with the appropriate information. This allows you to create multiple variations of your website's design while still relying on the same parent theme.

### How do I update the parent theme without losing the customizations in the child theme?

One of the main advantages of using a child theme is that you can safely update the parent theme without losing your customizations. When updating the parent theme, WordPress will only modify the files within the parent theme's folder and will not touch the files in the child theme's folder. Therefore, your customizations in the child theme will remain intact.

However, it's essential to review the parent theme's change log and test the updated parent theme with your child theme on a staging site or local environment to ensure compatibility. In some cases, significant changes in the parent theme may require adjustments to your child theme's code.
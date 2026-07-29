Adding custom PHP code to WordPress unlocks endless possibilities for customization. Whether you want to display notifications, modify functionality, or integrate third-party tools, PHP is your go-to language. However, inserting code incorrectly can break your site.  
In this guide, you’ll learn **three safe methods** to add PHP code to WordPress, including how to display a header notification. Let’s dive in!

Method 1: Add PHP Code via Child Theme’s functions.php
------------------------------------------------------

This method is perfect for theme-specific tweaks

### Step 1: Create a Child Theme (Essential!)

Editing a parent theme directly risks losing changes during updates. **Create a child theme** first.  
Follow this [step-by-step tutorial](https://redpishi.com/wordpress-tutorials/create-child-theme-in-wordpress-step-by-step-without-plugin/) to set one up.

### Step 2: Insert PHP Code into `functions.php`

1.  Go to **Appearance > Theme Editor** . (In Block Themes, go to **Tools** **\>** **Theme File Editor.**)
2.  Select your child theme.
3.  Open the `functions.php` file.
4.  Add this code to display a notification in the header:

    function my_header_notification() {
        echo '<div style="background:#f00; color:#fff; padding:10px; text-align:center;">Welcome to Our Site!</div>';
    }
    add_action('wp_head', 'my_header_notification');

Copy

This displays a notification in your site’s header.

**Pros**:

*   Safe for theme updates.
*   Ideal for theme-dependent code.

**Cons**:

*   Code won’t work if you switch themes.

Method 2: Using a Custom Plugin
-------------------------------

This method is Ideal for developers or long-term solutions. Plugins isolate functionality from themes, making them ideal for reusable features.

### **Build a Basic Plugin**

1.  Access your site via FTP/cPanel.
2.  Navigate to `**/wp-content/plugins/**`.
3.  Create a folder: `**my-plugin**`.
4.  Inside it, create `**my-plugin.php**` with this header:

    <?php
    /*
    Plugin Name: My plugin
    Description: Adds my custom codes to WordPress.
    Version: 1.0
    Author: Your Name
    */
    
    // Load notification code
    require_once plugin_dir_path(__FILE__) . 'includes/header-notification.php';

Copy

5\. Create an `**includes**` folder and add `**header-notification.php**`:

    <?php
    function my_header_notification() {
        echo '<div style="background:#0f0; color:#000; padding:10px; text-align:center;">Thanks for visiting our site!</div>';
    }
    add_action('wp_head', 'my_header_notification');

Copy

6\. Activate the plugin in **Plugins > Installed Plugins** .

Here, we created a separate file for the notification code and included it in the main plugin file.  
The advantage of this method is that you can create a separate file for each of your codes and include them in the main plugin file, which makes managing these codes very easy.

**Pros**:

*   **Independent** of **themes**.
*   **Modular architecture** simplifies updates and debugging.
*   **Portability**: Can be easily deactivated and reactivated without affecting the theme.

**Cons**:

*   Requires manual plugin setup.
*   Overkill for small tweaks.

**Method 3: Use a Helper Plugin (Code Snippets/FluentSnippets)**
----------------------------------------------------------------

Perfect for non-developers! Plugins like **Code Snippets** simplify adding code without touching files.

### Using Code Snippets

**Step 1: Install Code Snippets**

1.  Go to **Plugins > Add New**.
2.  Search for “Code Snippets”, install, and activate it.

**Step 2: Add Your PHP Code**

1.  Go to **Snippets > Add New**.
2.  Title it (e.g., “Header Notification”).
3.  Paste your code:

    function my_header_notification() {
        echo '<div style="background:#0f0; color:#000; padding:10px; text-align:center;">Thanks for visiting our site!</div>';
    }
    add_action('wp_head', 'my_header_notification');

Copy

4.  Under **Settings**, set the hook to **Run snippet everywhere**.
5.  Save and activate.

### Using FluentSnippets

FluentSnippets offers enhanced security and performance through file-based snippet storage:

1.  Install [FluentSnippets](https://wpmanageninja.com/fluentsnippets-wordpress-code-snippets-plugin/) from the plugin directory.
2.  Go to **FluentSnippets > Add New Snippet**.
3.  **Paste the same code** as above and configure conditions (e.g., display on specific pages).

**Pros**:

*   No coding skills required.
*   Snippets are easy to manage.

**Cons**:

*   Relies on third-party plugin security.
*   Too many snippets may slow your site.

### Choosing the Right Method

The best method depends on your specific needs and technical comfort level:

*   **Child Theme:** if you’re already using a child theme and want to keep your customizations theme-specific.
*   **Custom Plugin:** if you want your code to be portable and reusable across different themes or sites.
*   **Helper Plugin:** if you prefer a user-friendly interface and want to avoid directly editing theme files or creating plugins.

For most users, **helper plugins** like Code Snippets provide the safest and quickest way to inject PHP code, especially for one-off modifications.   
**Child themes** remain ideal for theme-specific customizations, while **custom plugins** suit complex, reusable functionality.

If this article is difficult for you to read in text, you can watch the video version below.


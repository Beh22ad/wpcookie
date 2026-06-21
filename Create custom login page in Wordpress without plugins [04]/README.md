# How to Create a Custom Login Page in WordPress Without a Plugin

This tutorial provides a fast and easy method to create a completely custom WordPress login page without using any plugin.

---

## Introduction

Instead of using the default `wp-login.php` page, you will create a new page (e.g., `yoursite.com/login`) with a custom design. The code provides three different visual templates: **Glass**, **Make Up**, and **Photography**.

---

## Step 1: Create a Page with the "Login" Title

1. In your WordPress dashboard, go to **Pages → Add New**.
2. Set the **title** exactly to `Login` (this is important for the code to work).
3. In the page settings, change the **template** to a full-screen layout and disable all page elements like title, sidebar, and paddings.

### Settings for Popular Themes

| Theme | Settings |
| :--- | :--- |
| **Twenty Twenty-Two** | Change page template to **Blank**. |
| **Blocksy / Kadence** | Set Content Area to **Wide**, disable top/bottom paddings, and disable Page Title and all Elements. |
| **Astra** | Set Content Layout to **Full Width**, disable Sidebar and all page Elements. |

---

## Step 2: Copy One of the Three Templates into functions.php

1. Go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Choose one of the three templates below, copy the code, and paste it into the file.
4. **Replace the logo and background image URLs** with your own.
5. Save the file.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

### 2.1. WordPress Login Page – Glass Template

This template features a glassmorphism effect with a blurred background.

```php
// redpishi.com login page glass template

add_filter( 'the_content', 'redpihi_custom_login' );
function redpihi_custom_login( $content ) {
$fullScreen = 1;	
$logo = 'https://redpishi.com/wp-content/uploads/2022/06/1.png';	// <-- REPLACE WITH YOUR LOGO URL
$bg ='https://redpishi.com/wp-content/uploads/2022/06/bg1-scaled.webp' ; // <-- REPLACE WITH YOUR BACKGROUND URL
$loging_users = '<p>Hello '.wp_get_current_user()->user_login.'! You are logged in.</p>';
if (is_page(get_page_by_title( 'login' )->ID)) {  
	if (is_user_logged_in()) {

		return	$content.$loging_users;	  } else {   
if ($fullScreen == 1) echo '<style>header {display: none;} footer {display: none;} body {overflow: hidden;}</style>';	?>

		<div class ="redpishi-login-form" >  
			<img id="rlogo" src="<?= $logo ?>" alt="" width="75" style="margin: 5px 0 25px 0;">

			<?php   wp_login_form( 
							array( 
								'echo' => true ,
								'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] .'/' ,
								'label_username' => __( 'Your Username ' ),
								'label_password' => __( 'Your Password' ),
								'label_remember' => __( 'Remember Me' )
			              )
			);  } ?> 

		</div>

<style>
body {
  margin: 0;
  background-image: url("<?= $bg ?>");
  background-color: white;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative; 
}

div.redpishi-login-form {
    border: 1px solid #ffffff3b;
    color: #020202;
    padding: 25px;
    border-radius: 5px;
    width: 300px!important;
    max-width: 90%!important;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: auto;
    margin-top: 100px;
    background-color: #95959545;
    backdrop-filter: blur(9px);
}
.redpishi-login-form input[type=text], .redpishi-login-form input[type=password] {
    border-radius: 5px;
    padding: 2px 10px;
    background-color: #ffffff00;
    font-size: 1.2rem;
    height: 38px;
    color: #ffffffde;
    border: 1px solid #ffffffbd;
    width: 100%;
}
.redpishi-login-form input:focus {
    color: white;
	border: 1px solid white;
}
.redpishi-login-form input#wp-submit {
    transition: 0.2s;
    cursor: pointer;
    padding: 7px 3px;
    background: #000000;
    border: none;
    width: 100%;
    font-size: 1.1rem;
    color: white;
    border-radius: 5px;
}
.log_erorr {
    color: white;
    background: red;
    padding: 0px 9px!important;
    border-radius: 3px;
    line-height: 2rem;
    width: 100%;
    margin-bottom: 15px;
}		
.redpishi-login-form input#wp-submit:hover {
    transform: translateY(-3px);
}	
.redpishi-login-form label {
    color: #ffffffde;
    font-size: 0.95rem;
    display: block;
}
</style>

<script>
const urlParams = new URLSearchParams(window.location.search); 
const login = urlParams.get('login');
if ( login == "failed" ) { 
let error = document.createElement("div");
error.className = "log_erorr";
let rlogo = document.querySelector('#rlogo')
rlogo.parentNode.insertBefore(error, rlogo.nextSibling);
error.innerHTML = "Wrong password. Try again..."
 }
</script>

<?php } else {
	return $content ;
}
}
add_action( 'wp_login_failed', 'my_front_end_login_fail' );  
function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' ); 
      exit;
   }
}
```

---

### 2.2. WordPress Login Page – Make Up Template

This template positions the login form on the left side with a clean, minimal design.

```php
// Redpishi.com WordPress login page Make Up Template

add_filter( 'the_content', 'redpihi_custom_login' );
function redpihi_custom_login( $content ) {
$fullScreen = 1;	
$logo = 'https://redpishi.com/wp-content/uploads/2022/06/2.png';	// <-- REPLACE WITH YOUR LOGO URL
$bg ='https://redpishi.com/wp-content/uploads/2022/06/bg2-scaled.webp'; // <-- REPLACE WITH YOUR BACKGROUND URL
$loging_users = '<p>Hello '.wp_get_current_user()->user_login.'! You are logged in.</p>';
if (is_page(get_page_by_title( 'login' )->ID)) {  
	if (is_user_logged_in()) {
		return	$content.$loging_users;	  } else {   
if ($fullScreen == 1) echo '<style>header {display: none;} footer {display: none;} body {overflow: hidden;}</style>';	?>	
		<div class ="redpishi-login-form" >  
			<img id="rlogo" src="<?= $logo ?>" alt="" width="75" style="margin: 5px 0 25px 0;" >	
			<?php   wp_login_form( 
							array( 
								'echo' => true ,
								'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] .'/' ,
								'label_username' => __( 'Your Username ' ),
								'label_password' => __( 'Your Password' ),
								'label_remember' => __( 'Remember Me' )
			              )
			);  } ?> 

		</div>
<style>

@media screen and (min-width: 750px) {
  body {
    background-image: url("<?= $bg ?>"); 
    background-position: 400px 0px;	
  }
div.redpishi-login-form {
    float: left;
    margin-left: 30px!important;
}}
body {
    margin: 0;
    background-color: white;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
    }
div.redpishi-login-form {
    color: #020202;
    padding: 23px;
    border: 1px solid #ffffff4a;
    border-radius: 5px;
    width: 320px!important;
    max-width: 90%!important;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: auto;
    margin-top: 40px;
}
.redpishi-login-form input:focus {
    color: #00131c;
    border: 1px solid #00131c;
}
.redpishi-login-form input[type=text], .redpishi-login-form input[type=password] {
    font-size: 1.2rem;
    height: 38px;
    color: #00131c;
    border: 1px solid #a4a4a4;
    width: 100%;
}
.redpishi-login-form input#wp-submit {
    transition: 0.2s;
    cursor: pointer;
    padding: 7px 3px;
    background: #a22faf;
    border: none;
    width: 100%;
    font-size: 1.1rem;
    color: white;
}
.log_erorr {
    color: white;
    background: red;
    padding: 0px 9px!important;
    border-radius: 3px;
    line-height: 2rem;
    width: 100%;
    margin-bottom: 15px;
}	
.redpishi-login-form input#wp-submit:hover {
    transform: translateY(-3px);
}	

</style>

<script>
const urlParams = new URLSearchParams(window.location.search); 
const login = urlParams.get('login');
if ( login == "failed" ) { 
let error = document.createElement("div");
error.className = "log_erorr";
let rlogo = document.querySelector('#rlogo')
rlogo.parentNode.insertBefore(error, rlogo.nextSibling);
error.innerHTML = "Wrong password. Try again..."
 }
</script>

<?php } else {
	return $content ;
}
}

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  
function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' ); 
      exit;
   }
}
```

---

### 2.3. WordPress Login Page – Photography Template

This template places the login form on the right side, ideal for visual-heavy sites like photography portfolios.

```php
// Redpishi.com WordPress login page Photography Template

add_filter( 'the_content', 'redpihi_custom_login' );
function redpihi_custom_login( $content ) {
$fullScreen = 1;	
$logo = 'https://redpishi.com/wp-content/uploads/2022/06/3.png';	// <-- REPLACE WITH YOUR LOGO URL
$bg ='https://redpishi.com/wp-content/uploads/2022/06/bg3-scaled.webp'; // <-- REPLACE WITH YOUR BACKGROUND URL
$loging_users = '<p>Hello '.wp_get_current_user()->user_login.'! You are logged in.</p>';
if (is_page(get_page_by_title( 'login' )->ID)) {  
	if (is_user_logged_in()) {	
		return	$content.$loging_users;	  } else {   
if ($fullScreen == 1) echo '<style>header {display: none;} footer {display: none;} body {overflow: hidden;}</style>';	?>

		<div class ="redpishi-login-form" >  
			<img id="rlogo"  src="<?= $logo ?>" alt="" width="75" style="margin: 5px 0 25px 0;" >

			<?php   wp_login_form( 
							array( 
								'echo' => true ,
								'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] .'/' ,
								'label_username' => __( 'Your Username ' ),
								'label_password' => __( 'Your Password' ),
								'label_remember' => __( 'Remember Me' )
			              )
			);  } ?> 

		</div>

<style>

@media screen and (min-width: 750px) {
  body {
    background-image: url("<?= $bg ?>"); 
    background-position: 0px 0px;	
  }
 div.redpishi-login-form {
     float: right;
    margin-right: 90px!important; 
 }}
body {
    margin: 0;
    background-color: white;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
    }
div.redpishi-login-form {
    background-color: white;
    color: #020202;
    padding: 23px;
    border: 1px solid #ffffff4a;
    border-radius: 5px;
    width: 300px!important;
    max-width: 90%!important;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: auto;
    margin-top: 90px;
}

.redpishi-login-form input:focus {
    color: #00131c;
    border: 1px solid #00131c;
}
.redpishi-login-form input[type=text], .redpishi-login-form input[type=password] {
    font-size: 1.2rem;
    height: 38px;
    color: #00131c;
    border: 1px solid #a4a4a4;
    width: 100%;
}
.redpishi-login-form input#wp-submit {
    transition: 0.2s;
    cursor: pointer;
    padding: 7px 3px;
    background: #000000;
    border: none;
    width: 100%;
    font-size: 1.1rem;
    color: white;
    border-radius: 5px;
}
.log_erorr {
    color: white;
    background: red;
    padding: 0px 9px!important;
    border-radius: 3px;
    line-height: 2rem;
    width: 100%;
    margin-bottom: 15px;
}	
.redpishi-login-form input#wp-submit:hover {
    transform: translateY(-3px);
}
.redpishi-login-form label {
    color: #626262;
    font-size: 0.95rem;
    display: block;
}
</style>

<script>
const urlParams = new URLSearchParams(window.location.search); 
const login = urlParams.get('login');
if ( login == "failed" ) { 
let error = document.createElement("div");
error.className = "log_erorr";
let rlogo = document.querySelector('#rlogo')
rlogo.parentNode.insertBefore(error, rlogo.nextSibling);
error.innerHTML = "Wrong password. Try again..."
 }
</script>

<?php } else {
	return $content ;
}
}

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  
function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' ); 
      exit;
   }
}
```

---

## Testing

After adding the code to `functions.php`, visit your new login page (e.g., `yoursite.com/login`) and test it.

---

## Code Explanation

- **`wp_login_form()`**: This WordPress function generates the login form. You can customize the labels for username, password, and remember me by modifying the array.
- **Logged-in Users**: If a user is already logged in, they will see a message:  
  `Hello [USERNAME]! You are logged in.`  
  You can customize this message by editing the `$loging_users` variable.
- **Custom CSS**: You can easily modify the design (colors, button size, padding, etc.) by editing the CSS section in the template.
- **Error Handling**: The code includes JavaScript to display a "Wrong password" message on failed login attempts.

---

## Customization Tips

- **Add reCAPTCHA**: To add Google reCAPTCHA to this form, you would need to integrate it with the `wp_login_form` using additional hooks.
- **Add Links**: You can add registration or "lost password" links by editing the PHP code and adding HTML links within the form container.
```
# Create Popups in WordPress Without Plugins

Create lightweight WordPress popups **without using any plugin or external library**.

This method lets you create two popup types:

- **Lead generation popup** that can save email leads to a CSV file
- **Sales offer popup** with a call-to-action button

## Features

- No popup plugin required
- No external JS/CSS libraries
- Lightweight and customizable
- Supports lead generation and sales popups
- Can be limited to specific pages using WordPress conditional tags

---

## How to Create Popups in WordPress Without Plugins

### Step 1 — Add the code to `functions.php`

Open your active theme’s `functions.php` file and paste the popup code into it.

> **Important:** Use a **child theme** before editing `functions.php`, otherwise your changes will be lost after theme updates.

You can find the file in:

- **WordPress Dashboard → Appearance → Theme File Editor**
- or directly on your server via FTP / file manager

### Popup code

```php
/*
* popup by WPCookie
* https://redpishi.com/wordpress-tutorials/popups-in-wordpress-without-plugins/
*/

function popup_r(){
if( !is_admin() ) {
$type= "a"; // a for lead generation form and b for sales offer
$how_many_times = 6;	// How many times should this pop-up be shown to each user?
$seconds_to_show = 5;
$image="https://i.postimg.cc/g265XXpg/newsletter-signup-examples.webp";	 // set it to "" if you don't want an image
$description = "Subscribe now for hand-picked holiday deals, inspiration and latest tips, straight to your inbox.";
$button	= "SUBSCRIBE";
$success_msg = 'You have successfully subscribed.';
$link = 'https://google.com/'; // redirection link for popup type b
?>
<div id="redmodal" class="redmodal hiden">
    <div class="redmodal-header">
        <div class="redmodal-title"></div>
        <button class="close-button" onclick="closeredmodal()"> &times; </button>
    </div>

    <div class="redmodal-body">
        <form id="leadGeneration" onsubmit="leadGenerationSub(); return false;">
            <?php if ($image) { ?>
                <img class="center-image" src="<?= $image; ?>" alt="Popup image">
            <?php } ?>

            <div id="description">
                <p id="description"><?= $description ?></p>
            </div>

            <label id="website" for="website">website:</label><br>
            <input type="text" id="website" name="website" autocomplete="off" placeholder="www.yoursite.com">

            <?php if ($type == 'a') { ?>
                <input type="email" id="email" name="email" placeholder="Email" required>
            <?php } ?>

            <input id="mybtn" class="red-pop" type="submit" value="<?= $button; ?>">
            <p class="close" onclick="closeredmodal()">No thanks, I'm just browsing</p>

            <input type="hidden" id="formid" name="formid" value="1005">
        </form>
    </div>
</div>

<div id="overlay" class="overlay" onclick="closeredmodal()"></div>

<style>
#website {display: none;}

#er {
    color: #f21515;
    background-color: #fff0f0;
    font-weight: bold;
    border: 3px solid #f21515;
    border-radius: 5px;
    padding: 10px;
    margin: 15px auto;
}

#success {
    color: #23a238;
    background-color: azure;
    font-weight: bold;
    border: 3px solid #23a238;
    border-radius: 5px;
    padding: 10px;
    margin: 15px auto;
}

.redmodal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    z-index: 5001;
    background: white;
    border-radius: 5px;
    box-shadow: 0px 5px 15px 0px #00000057;
    width: 500px;
    max-width: 90%;
    transition: 200ms ease-in-out;
    overflow: auto;
    max-height: 90%;
}

.redmodal.active {
    visibility: visible;
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

form#leadGeneration {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.redmodal-header {
    right: 10px;
    top: 10px;
    width: 100%;
    position: absolute;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 5002;
}

.redmodal-title {
    font-size: 1.2rem;
}

button.close-button {
    transition: 0.35s ease;
    line-height: 22px;
    padding: 0 5px;
    cursor: pointer;
    border: none;
    outline: none;
    background: none;
    font-size: 27px;
    color: #b0b0b0;
    border-radius: 1000px;
}

button.close-button:hover {
    color: red;
}

.redmodal-body {
    display: flex;
    padding: 31px 30px 10px 30px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

img.center-image {
    max-height: 35vh;
    max-width: 90%;
}

#overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    background-color: #000000c7;
    pointer-events: none;
    opacity: 0;
    transition: 200ms ease-in-out;
    z-index: 4000;
}

#overlay.active {
    opacity: 1;
    pointer-events: all;
}

.redmodal.hiden {
    display: none;
}

p#description {
    font-size: 1rem;
    margin-bottom: 0;
    margin-top: 10px;
}

input#email {
    width: 100%;
    height: 46px;
    border: 2px solid #dddddd;
    border-radius: 5px;
    font-size: 1rem;
    padding-left: 12px;
    transition: 0.5s ease;
}

input#email:focus {
    outline: none;
    border: 2px solid #7e7e7e;
}

input.red-pop {
    width: 100%;
    margin-top: 10px;
    height: 45px;
    color: white;
    background-color: #2e2e2e;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.5s ease;
}

input.red-pop:hover {
    background-color: #000000;
}

p.close {
    cursor: pointer;
    color: #717171;
    font-size: 1rem;
    margin: 7px 0 0 0;
}
</style>

<script>
function getCookie(cName) {
    const name = cName + "=";
    const cDecoded = decodeURIComponent(document.cookie);
    const cArr = cDecoded.split('; ');
    let res;
    cArr.forEach(val => {
        if (val.indexOf(name) === 0) res = val.substring(name.length);
    });
    return res;
}

function setCookie(cName, cValue, expDays) {
    // cookie logic here
}

function closeKp() {
    if (document.querySelector('form div#success')) {
        closeredmodal();
    }
}

if (show == true) {
    window.addEventListener('load', ShowPopup);
}

function ShowPopup() {
    setTimeout(() =>
        document.querySelector('#redmodal').classList.remove('hiden'),
    100);

    setTimeout(function() {
        document.querySelector('#overlay').classList.add('active');
        document.querySelector('#redmodal').classList.add('active');
    }, <?php echo $seconds_to_show * 1000 ?>);
}

function closeredmodal() {
    document.querySelector('#redmodal.active').classList.remove('active');
    document.querySelector('#overlay.active').classList.remove('active');
}
</script>

<?php
    }
}
add_action('wp_head', 'popup_r');

add_action('wp_ajax_submitPopUp','submitPopUp');
add_action('wp_ajax_nopriv_submitPopUp','submitPopUp');

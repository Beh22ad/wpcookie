# WordPress Popups Without Plugins

Create professional popups in WordPress without using any plugins or external libraries. This lightweight solution won't slow down your website and includes two types of popups: lead generation forms and sales offers.

## Features

- ✅ No plugins required
- ✅ No external libraries
- ✅ Lightweight and fast
- ✅ Two popup types: Lead Generation & Sales Offer
- ✅ Cookie-based display control
- ✅ CSV export for collected leads
- ✅ Customizable appearance
- ✅ AJAX form submission
- ✅ Spam protection

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Popup Types](#popup-types)
- [Customization](#customization)
- [Lead Management](#lead-management)
- [Conditional Display](#conditional-display)
- [Troubleshooting](#troubleshooting)
- [FAQ](#faq)

## Installation

### Prerequisites

Before making any changes, **create a child theme** to prevent losing your modifications after theme updates.

[Learn how to create a child theme in WordPress](https://redpishi.com/wordpress-tutorials/create-child-theme-in-wordpress-step-by-step-without-plugin/)

### Step 1: Add Code to functions.php

1. Navigate to **Appearance → Theme Editor** in your WordPress dashboard
2. Open the `functions.php` file
3. Copy and paste the following code at the end of the file:

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
$image="https://i.postimg.cc/g265XXpg/newsletter-signup-examples.webp";	 // set it to "" if If you don't want an image.
$description = "Subscribe now for hand-picked holiday deals, inspiration and latest tips, straight to your inbox.";
$button	= "SUBSCRIBE";
$success_msg = 'You have successfully subscribed.';
$link = 'https://google.com/'; // redirection link for popup type b
?>
<div id="redmodal" class="redmodal hiden">
    <div class="redmodal-header" >
    <div class="redmodal-title" >  </div>
    <button class="close-button" onclick="closeredmodal()"> &times; </button>
    </div>
<div class="redmodal-body">
<form id="leadGeneration" onsubmit="leadGenerationSub(); return false;">
<?php if ($image) { ?>
<img class="center-image" src="<?= $image; ?>" alt="Wordpress Design Development Essential Cheatsheets Free Ebook">
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
<p class="close" onclick="closeredmodal()">No thanks, I'm just browsing </p>

<input type="hidden" id="formid" name="formid" value="1005">
</form>
</div>
</div>
 <div id="overlay" class="overlay" onclick="closeredmodal()"> </div>
  <style>
    #website {display: none;}
    #er {
        color: #f21515;
        background-color: #fff0f0;
        font-weight: bold;
        border: 3px solid #f21515;
        border-radius: 5px;
        padding: 10px 10px;
        margin: 15px auto;
    }
    #success {
        color: #23a238;
        background-color: azure;
        font-weight: bold;
        border: 3px solid #23a238;
        border-radius: 5px;
        padding: 10px 10px;
        margin: 15px auto;
    }
    </style>
    <script>
function getCookie(cName) {
      const name = cName + "=";
      const cDecoded = decodeURIComponent(document.cookie);
      const cArr = cDecoded .split('; ');
      let res;
      cArr.forEach(val => {
          if (val.indexOf(name) === 0) res = val.substring(name.length);
      })
      return res;
}
function setCookie(cName, cValue, expDays) {
        let date = new Date();
        date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
}
let show = true
if (getCookie('popUpA')) {
if 	(parseInt(getCookie('popUpA')) > <?= $how_many_times; ?> ) {show = false;	}
setCookie('popUpA', parseInt(getCookie('popUpA')) + 1 , 7)
} else { setCookie('popUpA', 1, 7); }
const leadGeneration = document.querySelector("form#leadGeneration");
function leadGenerationSub() {
document.querySelector("form#leadGeneration #mybtn").disabled = "true"
document.querySelector("form#leadGeneration #mybtn").value = 'Please wait...'
var formdata = new FormData(leadGeneration);
formdata.append('action', 'submitPopUp')
AjaxCform(formdata)
}
<?php if ($type == 'a') { ?>
async function AjaxCform(formdata) {
  const url = location.protocol+ '//'+ window.location.hostname +'/wp-admin/admin-ajax.php?action=submitPopUp'
  const response = await fetch(url, {
      method: 'POST',
      body: formdata,
  });
  const data = await response.json();
	if (data['statuse'] == 'ok'){
			document.querySelector("form#leadGeneration").innerHTML = '<div id="success"><?= $success_msg; ?></div>'
		setCookie('popUpA', 100 , 7);
	} else if (data['statuse'] == 'er') {
			document.querySelector("form#leadGeneration").innerHTML =
`<div id="er">
			Ops, ${data['reply']}
			</div>
`}}
 <?php }else if ($type == 'b') { ?> function AjaxCform(formdata) { setCookie('popUpA', 100 , 7); window.location.href = "<?= $link ?>"; } <?php } ?>
    document.querySelector("#redmodal").addEventListener("DOMNodeInserted", ()=>{setTimeout(closeKp, 3000)});
    function closeKp(){if (document.querySelector('form div#success')) {closeredmodal();}}
      if (show == true) {
     window.addEventListener('load', ShowPopup); }
    function ShowPopup(){

	setTimeout( e =>
	document.querySelector('#redmodal').classList.remove('hiden'), 100)
	setTimeout(function() {
		document.querySelector('#overlay').classList.add('active');
		document.querySelector('#redmodal').classList.add('active');

	}, <?php echo $seconds_to_show * 1000 ?>)
}

    function closeredmodal() {document.querySelector('#redmodal.active').classList.remove('active');
        document.querySelector('#overlay.active').classList.remove('active');}
    </script>
  <style>
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
    flex-wrap: nowrap;
    align-content: center;
    justify-content: space-around;
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
    padding: 0px 5px;
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
    flex-wrap: nowrap;
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
    margin-bottom: 0px;
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
    margin: 7px 0px 0px 0px;
}
    </style>
<?php  }   }
add_action('wp_head', 'popup_r');

add_action('wp_ajax_submitPopUp','submitPopUp');
add_action('wp_ajax_nopriv_submitPopUp','submitPopUp');
function submitPopUp(){
$system = array();
if ($_POST['website'] != '') {
	$system['statuse']='er';
	$system['reply']='Suspicious activity was detected, That’s all we know.';
	echo json_encode($system, JSON_UNESCAPED_UNICODE);
    exit; die(); }
foreach($_POST as $key => $value) {
    if (strpos($value, '"') !== FALSE) {
	$system['statuse']='er';
	$system['reply']='The character entered in the form is not acceptable, please contact the site support.';
	echo json_encode($system, JSON_UNESCAPED_UNICODE);
	exit; die(); }}
function saveform($message) {
		 $FileName = "lead";  // change this to something like "list6506732_d86g"
         $timee = date("Y-m-d");
         $fn = ABSPATH . '/'.$FileName.'.csv';
         $fp = fopen($fn, 'a');
	     fputs($fp, $message.$timee."\n");
	     fclose($fp);
}
function form5() {
	unset($_POST['formid'], $_POST['website'], $_POST['action']);
	$system = array();
	$post = array();
	foreach($_POST as $key => $value) {
			if ((strpos($value, "@") !== FALSE)) {
				$post[] = filter_var($value,FILTER_SANITIZE_EMAIL).',';
			} else {$post[] = filter_var($value,FILTER_SANITIZE_STRING).',';}
		}
	$message = join(",",$post);
	$message .=$_SERVER['REMOTE_ADDR'].",";
	saveform($message);
	if ( true  )	{
				$system['statuse']='ok';
				$system['reply']='<div id="success">You have successfully subscribed.</div>';
				echo json_encode($system, JSON_UNESCAPED_UNICODE);
				exit; die();

		} else { $system['statuse']='er';
					$system['reply']='Sorry, There is a problem in sending your form.';
					echo json_encode($system, JSON_UNESCAPED_UNICODE);
					exit; die();
    				 }}
if ($_POST['formid']) {

	$id = $_POST['formid'];
	if ($id == '1005'){ form5(); }
	else {
	$system['statuse']='er';
	$system['reply']='The form is not authorized, please contact the site support.';
	echo json_encode($system, JSON_UNESCAPED_UNICODE);
	exit; die(); }}}

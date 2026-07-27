add_action('init', function() {
if (  !is_admin() && !current_user_can( 'manage_options') && $GLOBALS['pagenow'] !== 'wp-login.php'  )  { 

$message = "Sorry for the dust! We know its taking a while but sit tight and well be with you soon.";
$text_color = "white";	
$bg_image = "https://i.postimg.cc/MGJ4vFXj/ba1.jpg"; // https://i.postimg.cc/fy9PQm7t/marc-obiols-qc-ULHs-OGW6g-unsplash.webp
$bg_overlay = "rgb(0 0 0 / 60%)";
// $reopening_date = "Nov 7, 2022 12:00:00";
// $subscribe_list = "subscribe_list";

if (isset( $_POST["email"] ) && wp_verify_nonce($_POST['red_sub'], 'red_sub')) {
	$user_email= sanitize_email($_POST["email"]); 
	$timee = date("Y-m-d");
	$fn = ABSPATH . '/'.$subscribe_list.'.csv'; 
	$fp = fopen($fn, 'a');
	fputs($fp, $user_email.",".$timee."\n");
	fclose($fp);	
	setcookie("red-subscribe", "1", time() + (86400 * 30), "/");
	$form = "done";
}		
?>	
<div class="maintenance">
<h3 id="maintenance-msg"><?= $message; ?></h3>
<?php if ($reopening_date) { ?>	<h1 id="countdown"></h1> <?php } ?>
<?php if ($subscribe_list && !isset($_COOKIE["red-subscribe"]) && !$form) { ?>
<div class="sub-form">	
<p>Notify me when it's ready </p>
<form action="" method="POST">
<input type="email" id="email" name="email" placeholder="Email" required>
<input id="mybtn"  type="submit" value="Subscribe"> 
<input type="hidden" name="red_sub" value="<?php echo wp_create_nonce('red_sub'); ?>"/>		
</form>	
</div>
<?php } elseif( isset($_COOKIE["red-subscribe"]) || $form && $subscribe_list) { ?> <div class="sub-form"> <p>Thank you for the subscription. We'll be in touch with you as soon as possible. </p> </div>  <?php } ?>	
<script>
if (document.getElementById("countdown")) {
var countDownDate = new Date("<?= $reopening_date; ?>").getTime();
var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  document.getElementById("countdown").innerHTML = days + "d: " + hours + "h: "
  + minutes + "m: " + seconds + "s ";
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "Please wait ...";
  }
}, 1000);
}	
</script>
</div>
 <style>
.maintenance {
	color: <?= $text_color; ?>!important;
<?php if($bg_image) { ?>	background-image: url( <?= $bg_image; ?>);
    background-size: cover;
	background-position: center; <?php } ?>
<?php if($bg_overlay) { ?>	box-shadow: inset 0 0 0 4000px <?= $bg_overlay; ?>; <?php } ?>
    position: fixed;
    inset: 0;
    background-color: white;
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    flex-direction: column;
}
#maintenance-msg {
    width: 600px;
    max-width: 90%;
    text-align: center;
    line-height: normal;
} 
h1#countdown {
color: <?= $text_color; ?>!important;
}
div.sub-form p {
    text-align: center;
}
div.sub-form input {
    height: 30px;
	border-radius: 3px;
}
div.sub-form input#mybtn {
    background-color: #0372ff;
    color: white;
    cursor: pointer;
	border: none;
}	 
</style>	
<?php
 wp_die("", "Coming soon!"); 
}} );

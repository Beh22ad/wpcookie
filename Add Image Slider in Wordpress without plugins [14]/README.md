# How to Create an Image Slider in WordPress (Without Plugins)

This tutorial teaches you how to create responsive image sliders in WordPress without using any plugin. You'll learn two methods: a customizable shortcode-based slider and a simple method to convert the native Gallery block into a slider.

---

## Introduction

By default, WordPress does not have a built-in slider tool. Creating a slider can enhance user experience by displaying high-quality images in a slideshow format. This guide provides two methods to achieve this using custom code.

---

## Method 1: Create a Slider with a Shortcode

This method uses a custom shortcode `[slider]` that you can place on any page. Slides are built using **Cover blocks** grouped together with a class.

### Step 1: Add the Shortcode Code to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the following code and paste it at the end of the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
/*
 * Slider by redpishi.com
 * [slider height="450px" source="slider"]
 */
add_shortcode( 'slider', 'slider_func' );
function slider_func( $atts ) {
    $atts = shortcode_atts( array(
        'height' => '450px',
        'source' => 'slider',
    ), $atts, 'slider' );

    static $asearch_first_call = 1;
    $source = $atts["source"];
    $height = $atts["height"];

    $style0 = '<style>
    #prev{
        left:0;
        border-radius: 0 10px 10px 0;
    }
    #next{
        right:0;
        border-radius:10px 0 0 10px;
    }
    #prev svg,#next svg{
        width:100%;
        fill: #fff;
    }
    #prev:hover,
    #next:hover{
        background-color: rgba(0, 0, 0, 0.8);
    }
    </style>';

    $js0 = <<<EOD
    <script>
    const arrowHtml = '<span class="icon-arrow_back" id="prev"></span><span class="icon-arrow_forward" id="next"></span>'
    let arrowIconLeft = '<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="50px" id="Layer_1" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 512 512" width="50px" color="#fff" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="352,115.4 331.3,96 160,256 331.3,416 352,396.7 201.5,256 "/></svg>';
    </script>
    EOD;

    $style = '<style>
    .'.$source.'{
        position: relative;
        overflow: hidden;
        width: 100%!important;
        height: '.$height.';
        padding: 0px!important;
    }
    .'.$source.' > div {
        width: 100%!important;
        position: absolute;
        transition: all 1s;
        height: '.$height.';
        margin: 0px!important;
    }
    .'.$source.' > span{
        height: 50px;
        position: absolute;
        top:50%;
        margin:-25px 0 0 0;
        background-color: rgba(0, 0, 0, 0.4);
        color:#fff;
        line-height: 50px;
        text-align: center;
        cursor: pointer;
    }
    </style>';

    $js = <<<EOD
    <script>
    document.querySelector('.$source').innerHTML += arrowHtml;
    let slides$source = document.querySelectorAll('.$source > div');
    let slideSayisi$source = slides$source.length;
    let prev$source = document.querySelector('.$source #prev');
    let next$source = document.querySelector('.$source #next');
    prev$source.innerHTML = arrowIconLeft;
    next$source.innerHTML = arrowIconLeft;
    next$source.querySelector('svg').style.transform = 'rotate(180deg)';
    for (let index = 0; index < slides{$source}.length; index++) {
        const element{$source} = slides{$source}[index];
        element{$source}.style.transform = "translateX("+100*(index)+"%)";
    }
    let loop{$source} = 0 + 1000*slideSayisi{$source};
    function goNext{$source}(){
        loop{$source}++;
        for (let index = 0; index < slides{$source}.length; index++) {
            const element{$source} = slides{$source}[index];
            element{$source}.style.transform = "translateX("+100*(index-loop{$source}%slideSayisi{$source})+"%)";
        }
    }
    function goPrev{$source}(){
        loop{$source}--;
        for (let index = 0; index < slides{$source}.length; index++) {
            const element{$source} = slides{$source}[index];
            element{$source}.style.transform = "translateX("+100*(index-loop{$source}%slideSayisi{$source})+"%)";
        }
    }
    next{$source}.addEventListener('click',goNext{$source});
    prev{$source}.addEventListener('click',goPrev{$source});
    document.addEventListener('keydown',function(e){
        if(e.code === 'ArrowRight'){
            goNext{$source}();
        }else if(e.code === 'ArrowLeft'){
            goPrev{$source}();
        }
    });
    </script>
    EOD;

    if ( $asearch_first_call == 1 ) {
        $asearch_first_call++;
        return "{$style0}{$style}{$js0}{$js}";
    } elseif ( $asearch_first_call > 1 ) {
        $asearch_first_call++;
        return "{$style}{$js}";
    }
}
```

After updating the file, the `[slider]` shortcode will be available for use.

---

### Step 2: Design Your Slides Using Cover Blocks

1. Edit the page where you want the slider.
2. For **each slide**, add a **Cover block**.
3. Set a background image for each cover.
4. Add any content inside the cover block (e.g., text, buttons).
5. Adjust the overlay opacity (set to `0` if you don't need it).

---

### Step 3: Group All Cover Blocks

1. **Select all** the Cover blocks you've created.
2. Click the **three dots** menu and select **Group**.
3. In the **group block settings**, assign a **CSS class** to the group (e.g., `slider`).
   - This class name will be used in the shortcode's `source` parameter.

---

### Step 4: Add the Slider Shortcode

Below the grouped cover blocks, add a **Shortcode block** with the slider shortcode:

```
[slider height="450px" source="slider"]
```

**Shortcode Parameters:**
- **`source`**: The CSS class you assigned to the grouped covers (e.g., `slider`).
- **`height`**: The height of the slider (e.g., `450px`, `100vh`).

---

## Professional Version (With Autoplay and Multiple Sliders)

```php
/*
* slider by redpishi.com
* [slider height="450px" source="slider" scroll="3"]
*/
add_shortcode( 'slider', 'slider_func' );
function slider_func( $atts ) {
    $atts = shortcode_atts( array(
        'height' => '450px',
		'source' => 'slider',
        'scroll' => '0',
    ), $atts, 'slider' );
static $asearch_first_call = 1;
$source = $atts["source"];
$height = $atts["height"];
$scroll = $atts["scroll"];
$style0 = '<style>
#prev{
    left:0;
    border-radius: 0 10px 10px 0;
}
#next{
    right:0;
    border-radius:10px 0 0 10px;
}
#prev svg,#next svg{
    width:100%;
    fill: #fff;
}
#prev:hover,
#next:hover{
    background-color: rgba(0, 0, 0, 0.8);
}

</style>
';
$js0 = <<<EOD
<script>
const arrowHtml = '<span class="icon-arrow_back" id="prev"></span><span class="icon-arrow_forward" id="next"></span>'
let arrowIconLeft = '<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="50px" id="Layer_1" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 512 512" width="50px" color="#fff" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="352,115.4 331.3,96 160,256 331.3,416 352,396.7 201.5,256 "/></svg>';
let SliderInterval = [];
let SliderON = [];
function AutoScroll(id) {
	document.querySelector("."+id+" span.icon-arrow_forward").click();
}
</script>
EOD;
$style = '<style>
.'.$source.'{
    position: relative;
    overflow: hidden;
	width: 100%!important;
	height: '.$height.';
	padding: 0px!important;
}
.'.$source.' > div {
    width: 100%!important;
    position: absolute;
    transition: all 1s;
	height: '.$height.';
	margin: 0px!important;
 }
.'.$source.' > div {
	height: '.$height.';
 }
.'.$source.' > span{
    height: 50px;
    position: absolute;
    top:50%;
    margin:-25px 0 0 0;
    background-color: rgba(0, 0, 0, 0.4);
    color:#fff;
    line-height: 50px;
    text-align: center;
    cursor: pointer;
}
</style>';
$js = <<<EOD
<script>
document.querySelector('.$source').innerHTML += arrowHtml;
let slides$source = document.querySelectorAll('.$source > div');
let slideSayisi$source = slides$source.length;
let prev$source = document.querySelector('.$source #prev');
let next$source = document.querySelector('.$source #next');
prev$source.innerHTML = arrowIconLeft;
next$source.innerHTML = arrowIconLeft;
next$source.querySelector('svg').style.transform = 'rotate(180deg)';
for (let index = 0; index < slides{$source}.length; index++) {
    const element{$source} = slides{$source}[index];
    element{$source}.style.transform = "translateX("+100*(index)+"%)";
}
let loop{$source} = 0 + 1000*slideSayisi{$source};
function goNext{$source}(){
    loop{$source}++;
            for (let index = 0; index < slides{$source}.length; index++) {
                const element{$source} = slides{$source}[index];
                element{$source}.style.transform = "translateX("+100*(index-loop{$source}%slideSayisi{$source})+"%)";
            }
}
function goPrev{$source}(){
    loop{$source}--;
            for (let index = 0; index < slides{$source}.length; index++) {
                const element{$source} = slides{$source}[index];
                element{$source}.style.transform = "translateX("+100*(index-loop{$source}%slideSayisi{$source})+"%)";
            }
}
next{$source}.addEventListener('click',goNext{$source});
prev{$source}.addEventListener('click',goPrev{$source});
document.addEventListener('keydown',function(e){
    if(e.code === 'ArrowRight'){
        goNext{$source}();
    }else if(e.code === 'ArrowLeft'){
        goPrev{$source}();
    }
});
</script>
EOD;
if ( $scroll > 0 ) {
$js_scroll = <<<EOD
<script>
SliderInterval["{$source}"] = setInterval( function(){ AutoScroll("{$source}") }, {$scroll} * 1000);
SliderON["{$source}"] = 1;

document.querySelector(".{$source}").addEventListener('mousemove', function(){
	if ( SliderON["{$source}"] == 1 )  {
        clearInterval(SliderInterval["{$source}"]);
        SliderON["{$source}"] = 0;
    }
});
document.querySelector(".{$source}").addEventListener("pointerout", function(){
    if (SliderON["{$source}"] == 0) {
        SliderInterval["{$source}"] = setInterval(  function(){ AutoScroll("{$source}") } , {$scroll} * 1000);
        SliderON["{$source}"] = 1;
    }
});
</script>
EOD;
} else { $js_scroll = ""; }
$js = $js . $js_scroll;
if ( $asearch_first_call == 1 ){
	 $asearch_first_call++;
	 return "{$style0}{$style}{$js0}{$js}"; } elseif  ( $asearch_first_call > 1 ) {	$asearch_first_call++;
		return "{$s/*
* slider by redpishi.com
* [slider height="450px" source="slider" scroll="3"]
*/
add_shortcode( 'slider', 'slider_func' );
function slider_func( $atts ) {
    $atts = shortcode_atts( array(
        'height' => '450px',
		'source' => 'slider',
        'scroll' => '0',
    ), $atts, 'slider' );
static $asearch_first_call = 1;
$source = $atts["source"];
$height = $atts["height"];
$scroll = $atts["scroll"];
$style0 = '<style>
#prev{
    left:0;
    border-radius: 0 10px 10px 0;
}
#next{
    right:0;
    border-radius:10px 0 0 10px;
}
#prev svg,#next svg{
    width:100%;
    fill: #fff;
}
#prev:hover,
#next:hover{
    background-color: rgba(0, 0, 0, 0.8);
}

</style>
';
$js0 = <<<EOD
<script>
const arrowHtml = '<span class="icon-arrow_back" id="prev"></span><span class="icon-arrow_forward" id="next"></span>'
let arrowIconLeft = '<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="50px" id="Layer_1" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 512 512" width="50px" color="#fff" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="352,115.4 331.3,96 160,256 331.3,416 352,396.7 201.5,256 "/></svg>';
let SliderInterval = [];
let SliderON = [];
function AutoScroll(id) {
	document.querySelector("."+id+" span.icon-arrow_forward").click();
}
</script>
EOD;
$style = '<style>
.'.$source.'{
    position: relative;
    overflow: hidden;
	width: 100%!important;
	height: '.$height.';
	padding: 0px!important;
}
.'.$source.' > div {
    width: 100%!important;
    position: absolute;
    transition: all 1s;
	height: '.$height.';
	margin: 0px!important;
 }
.'.$source.' > div {
	height: '.$height.';
 }
.'.$source.' > span{
    height: 50px;
    position: absolute;
    top:50%;
    margin:-25px 0 0 0;
    background-color: rgba(0, 0, 0, 0.4);
    color:#fff;
    line-height: 50px;
    text-align: center;
    cursor: pointer;
}
</style>';
$js = <<<EOD
<script>
document.querySelector('.$source').innerHTML += arrowHtml;
let slides$source = document.querySelectorAll('.$source > div');
let slideSayisi$source = slides$source.length;
let prev$source = document.querySelector('.$source #prev');
let next$source = document.querySelector('.$source #next');
prev$source.innerHTML = arrowIconLeft;
next$source.innerHTML = arrowIconLeft;
next$source.querySelector('svg').style.transform = 'rotate(180deg)';
for (let index = 0; index < slides{$source}.length; index++) {
    const element{$source} = slides{$source}[index];
    element{$source}.style.transform = "translateX("+100*(index)+"%)";
}
let loop{$source} = 0 + 1000*slideSayisi{$source};
function goNext{$source}(){
    loop{$source}++;
            for (let index = 0; index < slides{$source}.length; index++) {
                const element{$source} = slides{$source}[index];
                element{$source}.style.transform = "translateX("+100*(index-loop{$source}%slideSayisi{$source})+"%)";
            }
}
function goPrev{$source}(){
    loop{$source}--;
            for (let index = 0; index < slides{$source}.length; index++) {
                const element{$source} = slides{$source}[index];
                element{$source}.style.transform = "translateX("+100*(index-loop{$source}%slideSayisi{$source})+"%)";
            }
}
next{$source}.addEventListener('click',goNext{$source});
prev{$source}.addEventListener('click',goPrev{$source});
document.addEventListener('keydown',function(e){
    if(e.code === 'ArrowRight'){
        goNext{$source}();
    }else if(e.code === 'ArrowLeft'){
        goPrev{$source}();
    }
});
</script>
EOD;
if ( $scroll > 0 ) {
$js_scroll = <<<EOD
<script>
SliderInterval["{$source}"] = setInterval( function(){ AutoScroll("{$source}") }, {$scroll} * 1000);
SliderON["{$source}"] = 1;

document.querySelector(".{$source}").addEventListener('mousemove', function(){
	if ( SliderON["{$source}"] == 1 )  {
        clearInterval(SliderInterval["{$source}"]);
        SliderON["{$source}"] = 0;
    }
});
document.querySelector(".{$source}").addEventListener("pointerout", function(){
    if (SliderON["{$source}"] == 0) {
        SliderInterval["{$source}"] = setInterval(  function(){ AutoScroll("{$source}") } , {$scroll} * 1000);
        SliderON["{$source}"] = 1;
    }
});
</script>
EOD;
} else { $js_scroll = ""; }
$js = $js . $js_scroll;
if ( $asearch_first_call == 1 ){
	 $asearch_first_call++;
	 return "{$style0}{$style}{$js0}{$js}"; } elseif  ( $asearch_first_call > 1 ) {	$asearch_first_call++;
		return "{$style}{$js}"; }}

tyle}{$js}"; }}
```

> For autoplay, the `[slider]` shortcode will accept a `scroll` parameter:
> ```
> [slider height="450px" source="slider" scroll="4"]
> ```

**Placeholder for professional slider code:**

```php
/* ========================================
   PROFESSIONAL SLIDER CODE (ADD YOUR CODE HERE)
   Supports: Autoplay, Multiple sliders per page
   ======================================== */
// [slider height="450px" source="slider" scroll="4"]
// --- YOUR CODE STARTS HERE ---
// Add the full professional slider code here.
// --- YOUR CODE ENDS HERE ---
```

---

## Method 2: Convert a Gallery Block into an Image Slider

This simpler method transforms a native WordPress Gallery block into an image slider.

### Step 1: Add the Conversion Code to functions.php

Copy and paste the following code into your **child theme's `functions.php`** file:

```php
/**
 * Image slider in WordPress by WPCookie - Convert Gallery block into image slider
 * https://redpishi.com/wordpress-tutorials/image-slider-in-wordpress-without-plugins/
 */
add_filter( 'wp_footer', function ( ) {  ?>
<script>
document.querySelectorAll(".wp-slider")?.forEach( slider => {
    var src = [];
    var alt = [];
    var img = slider.querySelectorAll("img");
    img.forEach( e => { src.push(e.src);   if (e.alt) { alt.push(e.alt); } else { alt.push("image"); } })
    let i = 0;
    let image = "";
    let myDot = "";
    src.forEach ( e => { image = image + `<div class="wpcookie-slide" > <img decoding="async" loading="lazy" src="${src[i]}" alt="${alt[i]}" > </div>`; i++ })
    i = 0;
    src.forEach ( e => { myDot = myDot + `<span class="wp-dot"></span>`; i++ })
    let dotDisply = "none";
    if (slider.classList.contains("dot")) dotDisply = "block";
    const main = `<div class="wp-slider">${image}<span class="wpcookie-controls wpcookie-left-arrow"  > <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="35" height="35" color="#fff" style="enable-background:new 0 0 50 50" viewBox="0 0 512 512"><path d="M352 115.4 331.3 96 160 256l171.3 160 20.7-19.3L201.5 256z"/></svg> </span> <span class="wpcookie-controls wpcookie-right-arrow" > <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="35" height="35" color="#fff" style="enable-background:new 0 0 50 50" viewBox="0 0 512 512"><path d="M180.7 96 352 256 180.7 416 160 396.7 310.5 256 160 115.4z"/></svg> </span> <div class="dots-con" style="display: ${dotDisply}"> ${myDot}</div></div> `
    slider.insertAdjacentHTML("afterend", main);
    slider.remove();
})

document.querySelectorAll(".wp-slider")?.forEach( slider => {
    var slides = slider.querySelectorAll(".wpcookie-slide");
    var dots = slider.querySelectorAll(".wp-dot");
    var index = 0;
    slider.addEventListener("click", e => {if(e.target.classList.contains("wpcookie-left-arrow")) { prevSlide(-1)} } )
    slider.addEventListener("click", e => {if(e.target.classList.contains("wpcookie-right-arrow")) { nextSlide(1)} } )
    function prevSlide(n){
        index+=n;
        changeSlide();
    }
    function nextSlide(n){
        index+=n;
        changeSlide();
    }
    changeSlide();
    function changeSlide(){
        if(index>slides.length-1)
            index=0;
        if(index<0)
            index=slides.length-1;
        for(let i=0;i<slides.length;i++){
            slides[i].style.display = "none";
            dots[i].classList.remove("wpcookie-slider-active");
        }
        slides[index].style.display = "block";
        dots[index].classList.add("wpcookie-slider-active");
    }
})
</script>

<style>
.wp-slider * {
    padding: 0;
    margin: 0;
}
.wp-slider{
    margin:0 auto;
    position:relative;
    overflow:hidden;
}
.wpcookie-slide{
    max-height: 100%;
    width:100%;
    display:none;
    animation-name:fade;
    animation-duration:1s;
}
.wpcookie-slide img{
    width:100%;
}
@keyframes fade{
    from{opacity:0.5;}
    to{opacity:1;}
}
.wpcookie-controls{
    position:absolute;
    top:50%;
    font-size:1.5em;
    padding:15px 10px;
    border-radius:5px;
    background:white;
    cursor: pointer;
    transition: 0.3s all ease;
    opacity: 15%;
    transform: translateY(-50%);
}
.wpcookie-controls:hover{
    opacity: 60%;
}
.wpcookie-left-arrow{
    left:0px;
    border-radius: 0px 5px 5px 0px;
}
.wpcookie-right-arrow{
    right:0px;
    border-radius: 5px 0px 0px 5px;
}
.wp-slider svg {
    pointer-events: none;
}
.dots-con{
    text-align:center;
}
.wp-dot{
    display:inline-block;
    background:#c4c4c4;
    padding:8px;
    border-radius:50%;
    margin:10px 5px;
}
.wpcookie-slider-active{
    background:#818181;
}
@media (max-width:576px){
    .wp-slider{width:100%;}
    .wpcookie-controls{
        font-size:1em;
        position: absolute;
        display: flex;
        align-items: center;
    }
    .dots-con{
        display:none;
    }
}
</style>
<?php });
```

### Step 2: Add a Gallery Block

1. Edit your page and add a **Gallery block**.
2. Upload or select your images (ideally with the same size).
3. In the block settings, set **IMAGE SIZE** to **Full Size**.
4. **Turn off** the **Crop images** option.
5. In the block's **Advanced** settings (under "Additional CSS class(es)"), assign the class:
   - `wp-slider` (to enable the slider).
   - `wp-slider dot` (to display dots below the slider).

Publish the page. The Gallery block will now function as an image slider.

---

## Customization Tips

- **Autoplay for Method 1:** To add simple autoplay, paste this custom HTML block after the shortcode:
  ```html
  <script>
  let speed = 4; // seconds
  document.addEventListener("DOMContentLoaded", function(){
      setTimeout( () =>
          setInterval(function () {document.querySelector("span.icon-arrow_forward#next").click();}, speed * 1000), 500)
  });
  </script>
  ```
- **Styling:** Modify the CSS in the code to match your theme's design (colors, fonts, arrow size, etc.).
- **Responsive:** Adjust the `height` parameter for different devices using media queries in your theme's CSS.

---

## Frequently Asked Questions

### Can I add more than one slider to a single page?

- **Method 1 (Shortcode):** The standard code only supports one slider per page. The **professional version** (placeholder provided) supports multiple sliders.
- **Method 2 (Gallery):** You can add multiple Gallery blocks with the `wp-slider` class; each will become a separate slider.

### How do I make the slider autoplay?

- For **Method 1**, use the autoplay script in the customization section above, or use the `scroll` parameter in the professional version.
- For **Method 2**, you would need to add custom JavaScript to the code provided.

### Can I use the slider in a widget or sidebar?

Yes. For **Method 1**, you can add the shortcode to a **Custom HTML widget** or use the **Shortcode widget**. For **Method 2**, you cannot place a Gallery block in a widget area directly, but you could use a plugin or custom code.

### How do I add captions to the Gallery block slider?

The current code does not support captions. To add captions, you would need to modify the JavaScript to extract and display the `data-caption` attribute from the gallery images.

### What if I only have one image in the slider?

The slider code works best with multiple slides. For one image, consider using a single Cover block or an Image block instead.

# How to Add a Post and Product Carousel in WordPress (Without Plugins)

This tutorial teaches you how to add a responsive carousel for posts or products to your WordPress site without using any plugin. The carousel automatically updates when you add new posts or products.

---

## Introduction

This carousel is built using a custom shortcode that works with **Query Loop blocks** (for posts) or **WooCommerce blocks** (for products). It is responsive and adapts to different screen sizes.

---

## Step 1: Add the Carousel Code to functions.php

1. In your WordPress dashboard, go to **Appearance → Theme File Editor**.
2. Open your **child theme's `functions.php`** file.
3. Copy the following code and paste it at the end of the file. Save the changes.

> **Important:** You **must** use a child theme to avoid losing changes during theme updates.
> *Alternative:* Use the **Code Snippets** plugin to insert the code safely.

```php
/*
 * Carousel by redpishi.com
 * [carousel source='class-name' num='4']
 */
add_shortcode( 'carousel', 'carousel_func' );
function carousel_func( $atts ) {
    $atts = shortcode_atts( array(
        'num' => '4',
        'source' => 'carousel',
    ), $atts, 'carousel' );

    static $carousel_first_call = 1;
    $source = $atts["source"];

    switch ($atts["num"]) {
        case "1":
            $num = "4000";
            break;
        case "2":
            $num = "450";
            break;
        case "3":
            $num = "450, 768";
            break;
        case "4":
            $num = "350, 768, 992";
            break;
        case "5":
            $num = "350, 768, 992, 1100";
            break;
        case "6":
            $num = "350, 576, 768, 992, 1100";
            break;
        case "7":
            $num = "350, 576, 768, 992, 1100, 1250";
            break;
        case "8":
            $num = "250,350, 576, 768, 992, 1100, 1250";
            break;
        case "9":
            $num = "350, 450, 550, 576, 768, 992, 1100, 1250";
            break;
        case "10":
            $num = "200, 350, 450, 550, 576, 768, 992, 1100, 1250";
            break;
        default:
            $num = "576, 768, 992";
    }

    $const = '<style>
    #prev, #next{
        padding: 0px;
        height: 35px;
        position: absolute;
        top:50%;
        margin:-25px 0 0 0;
        background-color: rgba(0, 0, 0, 0.4);
        color:#fff;
        line-height: 40px;
        text-align: center;
        cursor: pointer;
    }
    #prev {
        left: 0;
        border-radius: 50%;
    }
    #next {
        right: 0;
        border-radius: 50%;
    }
    #prev svg,#next svg{
        width:100%;
        fill: #fff;
    }
    #prev:hover,
    #next:hover{
        background-color: rgba(0, 0, 0, 0.8);
    }
    .carousel {
        margin: auto;
        overflow: hidden;
        padding: 0 10px;
        position: relative;
        background-color: #ffffff;
    }
    .carousel ul {
        list-style-type: none;
        margin: 0;
        overflow: hidden;
        transition: 0.7s;
        display: flex!important;
        flex-direction: row;
        flex-wrap: nowrap;
        padding: 0px 0px 33px 0px;
        align-items: stretch;
        align-content: stretch;
        max-width: unset!important;
    }
    .carousel ul li {
        background-color: white;
        margin: 0 16px -4px 0;
        min-height: 150px;
        height: max-content;
        flex: unset!important;
        padding: 7px 0px!important;
        box-shadow: 0px 16px 32px -16px #dedede;
    }
    .carousel ul li img {
        width: 100%;
    }
    </style>';

    $const2 = <<< JS
    <script>
    class Slider {
        constructor(id, mediaQueries) {
            this.arrowHtml = '<span class="slider-arrow-prev" id="prev"></span><span class="slider-arrow-next" id="next"></span>'
            this.leftArrow = '<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="35px" id="Layer_1" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 512 512" width="35px" color="#fff" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="352,115.4 331.3,96 160,256 331.3,416 352,396.7 201.5,256 "/></svg>';
            if (document.querySelector('.'+id).classList.contains("wc-block-all-products")) {
                this.slider = document.querySelector('div.'+id+':nth-child(2)');
            } else {
                this.slider = document.querySelector('.'+id);
            }
            this.slider.innerHTML += this.arrowHtml;
            this.sliderList = this.slider.querySelector("ul");
            this.sliderList.removeAttribute("class");
            this.sliderItems = this.slider.querySelectorAll("ul li");
            this.sliderNext = this.slider.querySelector(".slider-arrow-next");
            this.sliderPrev = this.slider.querySelector(".slider-arrow-prev");
            this.sliderPrev.innerHTML = this.leftArrow;
            this.sliderNext.innerHTML = this.leftArrow;
            this.sliderNext.querySelector('svg').style.transform = 'rotate(180deg)';
            this.mediaQueryList = [
                window.matchMedia('screen and (max-width:'+ mediaQueries[0] - 1+'px)')
            ];
            mediaQueries.forEach((mediaQuery) => {
                this.mediaQueryList.push(
                    window.matchMedia('screen and (min-width:'+mediaQuery+'px)')
                );
            });
            this.numberOfVisibleItems = null;
            this.currentItemIndex = null;
            this.sliderItemsLength = this.sliderItems.length;
            this.mediaQueryLength = this.mediaQueryList.length;
            this.mediaQueryList.forEach((mediaQuery) => {
                mediaQuery.addEventListener("change", () => {
                    this.run();
                });
            });
            this.sliderNext.addEventListener("click", () => {
                if (this.currentItemIndex < this.sliderItemsLength - this.numberOfVisibleItems) {
                    this.currentItemIndex++;
                    this.shiftSlides();
                } else {
                    this.currentItemIndex = null;
                    this.shiftSlides();
                }
            });
            this.sliderPrev.addEventListener("click", () => {
                if (this.currentItemIndex > 0) {
                    this.currentItemIndex--;
                    this.shiftSlides();
                } else {
                    this.currentItemIndex = this.sliderItemsLength - this.numberOfVisibleItems;
                    this.shiftSlides();
                }
            });
        }
        run() {
            let index = this.mediaQueryLength - 1;
            while (index >= 0) {
                if (this.mediaQueryList[index].matches) {
                    this.numberOfVisibleItems = index + 1;
                    this.currentItemIndex = 0;
                    this.sliderList.style.transform = "translateX(0)";
                    this.sliderList.style.width = 'calc(' +
                        (100 / this.numberOfVisibleItems) * this.sliderItemsLength +
                        '% + ' + (this.sliderItemsLength / this.numberOfVisibleItems) * 16 + 'px)';
                    this.sliderItems.forEach((item) => {
                        item.style.width = 100 / this.numberOfVisibleItems + '%';
                    });
                    break;
                }
                index--;
            }
        }
        shiftSlides() {
            this.sliderList.style.transform = 'translateX(' +
                (100 / this.sliderItemsLength) * -1 * this.currentItemIndex +
                '%)';
        }
    }
    </script>
    JS;

    $carousel = '
    <script>
    document.addEventListener("DOMContentLoaded", (event) => {
        new Slider("'.$source.'", [ '.$num.']).run();
    })
    </script> ';

    if ( $carousel_first_call == 1 ) {
        $carousel_first_call++;
        return "{$const}{$const2}{$carousel}";
    } elseif ( $carousel_first_call > 1 ) {
        $carousel_first_call++;
        return "{$carousel}";
    }
}
```

After updating the file, the `[carousel]` shortcode will be available for use.

---

## Step 2: Design Your Slides Using Gutenberg Blocks

You can use either a **Query Loop block** (for posts) or **WooCommerce blocks** (for products) as the source for your carousel.

### Option 2.1: Using a Query Loop Block (for Posts)

1. Edit your page and add a **Query Loop** block.
2. Configure the settings:
   - **View type:** Select **Grid**.
   - **Items Per Page:** Set to `10` or more.
   - **Max page to show:** Set to `1`.
3. In the block's **Advanced** settings (under "Additional CSS class(es)"), assign these two classes:
   - `carousel`
   - `carousel-a` (you can change this to any name, but it will be used in the shortcode)

### Option 2.2: Using WooCommerce Blocks (for Products)

1. Edit your page and add a WooCommerce block (e.g., **Products by Category**).
2. Configure the settings:
   - **Columns:** Set to `2` or `3`.
   - **Rows:** Set to `5` or `6` (ensure at least 10 products for a smooth carousel).
3. In the block's **Advanced** settings, assign the same two classes:
   - `carousel`
   - `carousel-a`

> **Note:** The `carousel` class is used for styling. The second class (e.g., `carousel-a`) is the `source` that you will reference in the shortcode.

---

## Step 3: Add the Carousel Shortcode

Below your Query Loop or WooCommerce block, add a **Shortcode block** with the carousel shortcode:

```
[carousel source='carousel-a' num='4']
```

**Shortcode Parameters:**
- **`source`**: The second class you assigned to your block (e.g., `carousel-a`).
- **`num`**: Number of slides to display per page (breakpoints are automatically set based on this number).

**Example Values for `num`:**
- `num='1'` – Very large items (mobile-first, shows 1 item).
- `num='2'` – Shows 2 items on larger screens.
- `num='3'` – Shows 3 items.
- `num='4'` – Shows 4 items (default).
- `num='5'` – Shows 5 items on large screens.
- `num='6'` – Shows 6 items.
- `num='7'` – Shows 7 items.
- `num='8'` – Shows 8 items.
- `num='9'` – Shows 9 items.
- `num='10'` – Shows 10 items on the largest screens.

---

## Professional Version (With Autoplay)

```php
/*
* carousel by redpishi.com
* [carousel source='class-name' num='4' scroll='4']
*/
add_shortcode( 'carousel', 'carousel_func' );
function carousel_func( $atts ) {
    $atts = shortcode_atts( array(
        'num' => '4',
		'source' => 'carousel',
        'scroll' => '0',
    ), $atts, 'carousel' );
static $carousel_first_call = 1;
$source = $atts["source"];
$scroll = $atts["scroll"];
switch ($atts["num"]) {
  case "1":
    $num = "4000";
    break;
  case "2":
    $num = "450";
    break;
  case "3":
    $num = "450, 768";
    break;
  case "4":
    $num = "350, 768, 992";
    break;
  case "5":
    $num = "350, 768, 992, 1100";
    break;
  case "6":
    $num = "350, 576, 768, 992, 1100";
    break;
  case "7":
    $num = "350, 576, 768, 992, 1100, 1250";
    break;
  case "8":
    $num = "250,350, 576, 768, 992, 1100, 1250";
    break;
  case "9":
    $num = "350, 450, 550, 576, 768, 992, 1100, 1250";
    break;
  case "10":
    $num = "200, 350, 450, 550, 576, 768, 992, 1100, 1250";
    break;
  default:
    $num = "576, 768, 992";
}
$const = '<style>
#prev, #next{
    padding: 0px;
    height: 35px;
    position: absolute;
    top:50%;
    margin:-25px 0 0 0;
    background-color: rgba(0, 0, 0, 0.4);
    color:#fff;
    line-height: 40px;
    text-align: center;
    cursor: pointer;
}
#prev {
    left: 0;
    border-radius: 50%;
}
#next {
    right: 0;
    border-radius: 50%;
}
#prev svg,#next svg{
    width:100%;
    fill: #fff;
}
#prev:hover,
#next:hover{
    background-color: rgba(0, 0, 0, 0.8);
}
.carousel {
    margin: auto;
    overflow: hidden;
    padding: 0 10px;
    position: relative;
    background-color: #ffffff;
}
.carousel ul {
    list-style-type: none;
    margin: 0;
    overflow: hidden;
    transition: 0.7s;
    display: flex!important;
    flex-direction: row;
    flex-wrap: nowrap;
    padding: 0px 0px 33px 0px;
    align-items: stretch;
    align-content: stretch;
    max-width: unset!important;
}
.carousel ul li {
    background-color: white;
    margin: 0 16px -4px 0;
    min-height: 150px;
    height: max-content;
    flex: unset!important;
    padding: 7px 0px!important;
    box-shadow: 0px 16px 32px -16px #dedede;
}
.carousel ul li img {
  width: 100%;
}
</style>';
$const2 = <<< JS
<script>
  class Slider {
    constructor(id, mediaQueries) {
      this.arrowHtml = '<span class="slider-arrow-prev" id="prev"></span><span class="slider-arrow-next" id="next"></span>'
      this.leftArrow = '<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="35px" id="Layer_1" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 512 512" width="35px" color="#fff" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="352,115.4 331.3,96 160,256 331.3,416 352,396.7 201.5,256 "/></svg>';
	  if (document.querySelector('.'+id).classList.contains("wc-block-all-products")) {
	   this.slider = document.querySelector('div.'+id+':nth-child(2)');
	  } else {
	  this.slider = document.querySelector('.'+id); }
      this.slider.innerHTML += this.arrowHtml;
      this.sliderList = this.slider.querySelector("ul");
	  this.sliderList.removeAttribute("class");
      this.sliderItems = this.slider.querySelectorAll("ul li");
      this.sliderNext = this.slider.querySelector(".slider-arrow-next");
      this.sliderPrev = this.slider.querySelector(".slider-arrow-prev");
      this.sliderPrev.innerHTML = this.leftArrow;
      this.sliderNext.innerHTML = this.leftArrow;
      this.sliderNext.querySelector('svg').style.transform = 'rotate(180deg)';
      this.mediaQueryList = [
        window.matchMedia('screen and (max-width:'+ mediaQueries[0] - 1+'px)')
      ];
      mediaQueries.forEach((mediaQuery) => {
        this.mediaQueryList.push(
          window.matchMedia('screen and (min-width:'+mediaQuery+'px)')
        ); });
      this.numberOfVisibleItems = null;
      this.currentItemIndex = null;
      this.sliderItemsLength = this.sliderItems.length;
      this.mediaQueryLength = this.mediaQueryList.length;
      this.mediaQueryList.forEach((mediaQuery) => {
        mediaQuery.addEventListener("change", () => {
          this.run();
        });
      });
      this.sliderNext.addEventListener("click", () => {
        if (
          this.currentItemIndex <
          this.sliderItemsLength - this.numberOfVisibleItems
        ) {
          this.currentItemIndex++;
          this.shiftSlides();
        } else {
          this.currentItemIndex = null
          this.shiftSlides();
        }
      });
      this.sliderPrev.addEventListener("click", () => {
        if (this.currentItemIndex > 0) {
          this.currentItemIndex--;
          this.shiftSlides();
        }else {
          this.currentItemIndex = this.sliderItemsLength - this.numberOfVisibleItems
          this.shiftSlides();
        }
      });
    }
    run() {
      let index = this.mediaQueryLength - 1;
      while (index >= 0) {
        if (this.mediaQueryList[index].matches) {
          this.numberOfVisibleItems = index + 1;
          this.currentItemIndex = 0;
          this.sliderList.style.transform = "translateX(0)";
			this.sliderList.style.width = 'calc(' +
            (100 / this.numberOfVisibleItems) * this.sliderItemsLength
          +'% + ' + (this.sliderItemsLength / this.numberOfVisibleItems) * 16 +'px)';
          this.sliderItems.forEach((item) => {
            item.style.width = 100 / this.numberOfVisibleItems + '%';
          });
          break;
        }
	index--; }}
    shiftSlides() {
      this.sliderList.style.transform = 'translateX('+
        (100 / this.sliderItemsLength) * -1 * this.currentItemIndex
  +'%'; }}

let CarouselInterval = [];
let CarouselON = [];
function AutoCarouselScroll(id) {
    document.querySelector(".carousel."+id+" span.slider-arrow-next").click();
}

</script>
JS;
$carousel = '
<script>
document.addEventListener("DOMContentLoaded", (event) => {
  new Slider("'.$source.'", [ '.$num.']).run();
})
</script> ';

if ( $scroll > 0 ) {
$js_scroll = <<<EOD
<script>
CarouselInterval["{$source}"] = setInterval( function(){ AutoCarouselScroll("{$source}") }, {$scroll} * 1000);
CarouselON["{$source}"] = 1;

document.querySelector(".{$source}").addEventListener('mousemove', function(){
	if ( CarouselON["{$source}"] == 1 )  {
        clearInterval(CarouselInterval["{$source}"]);
        CarouselON["{$source}"] = 0;
    }
});
document.querySelector(".{$source}").addEventListener("pointerout", function(){
    if (CarouselON["{$source}"] == 0) {
        CarouselInterval["{$source}"] = setInterval(  function(){ AutoCarouselScroll("{$source}") } , {$scroll} * 1000);
        CarouselON["{$source}"] = 1;
    }
});
</script>
EOD;
} else { $js_scroll = ""; }

$carousel = $carousel . $js_scroll;

if ( $carousel_first_call == 1 ){
	 $carousel_first_call++;
	 return "{$const}{$const2}{$carousel}"; } elseif  ( $carousel_first_call > 1 ) {	$carousel_first_call++;
		return "{$carousel}"; }}
```
> The pro version will accept a `scroll` parameter for autoplay:
> ```
> [carousel source='class-name' num='4' scroll='4']
> ```
> Where `scroll` is the time in seconds between automatic slides. Set to `0` to disable autoplay.


```php
/* ========================================
   PROFESSIONAL CAROUSEL CODE (ADD YOUR CODE HERE)
   Supports: Autoplay, Multiple carousels per page
   ======================================== */
// [carousel source='class-name' num='4' scroll='4']
// --- YOUR CODE STARTS HERE ---
// Add the full professional carousel code here.
// Ensure it handles the 'scroll' parameter for autoplay.
// --- YOUR CODE ENDS HERE ---
```

---

## Customization Tips

- **Styling:** You can customize the appearance of the carousel by editing the CSS inside the code (the `$const` variable). Look for selectors like `.carousel`, `.carousel ul li`, `#prev`, and `#next`.
- **Number of visible items:** The `num` parameter determines the breakpoints. The code automatically generates responsive settings for the number of slides you specify.
- **Looping:** The carousel loops back to the beginning when it reaches the end (and vice versa).

---

## Frequently Asked Questions

### Can I use this carousel with custom post types?

Yes. The Query Loop block can be configured to display any post type, including custom post types. Just set up the Query Loop to show your desired post type and assign the `carousel` class.

### Why are my items not showing in a row?

Ensure that:
1. The Query Loop or WooCommerce block is set to **Grid** view (not List).
2. The `carousel` class is correctly applied to the block.
3. The `source` parameter in the shortcode matches the second class you assigned.

### What if I have fewer items than the number of columns?

The carousel works best with at least 10 items. If you have fewer, the carousel will still work but may show empty space. Adjust the `Items Per Page` or ensure you have enough posts/products.

### Can I add more than one carousel to a single page?

Yes, but you need to use the **professional version** which supports multiple carousels. The standard code above is designed for a single carousel per page.

### How do I make the carousel autoplay?

Use the professional version. You will be able to add the `scroll` parameter to enable autoplay with a defined interval.

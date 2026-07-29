<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

<div class="<?= $attributes["align"] ?> swiffy-slider slider-nav-round slider-nav-sm slider-item-show2-sm slider-indicators-round <?=$attributes["theme"] ?> <?=$attributes["indicators"] ?> <?=$attributes["navigation"] ?> <?=$attributes["layout"] ?> <?php if ($attributes["autoPlay"] == true)
{
    echo "slider-nav-autoplay";
    if ($attributes["pauseOnHover"] == true)
    {
        echo " slider-nav-autopause";
    }
} ?>">

 <ul class="slider-container">
	
	<?php
	
	$cover_class = ( $attributes["align"] == "alignfull" ) ? ["class" => "cover_class"] : "";


$i = 1;
foreach ($attributes["images"] as $image)
{
    echo '<li><div id="slide' . $i . '" class="wpcookie-slide-parent">
		<div class="wpcookie-slide" style="max-height: ' . $attributes["height"] . '; ">
		';
    $id = $image["id"];
    $caption = end($image);

    echo wp_get_attachment_image($id, $attributes["imageSize"], "", $cover_class);
    if ($attributes["showCaption"] == true && is_string($caption) && strlen($caption) > 1)
    {
        echo "<p class='wpcookie-slider-caption'>" . $caption . "</p>";
    }

    echo '</div></div></li>';
    $i++;
}

?>  
	 </ul>
	    <button type="button" class="slider-nav"></button>
    <button type="button" class="slider-nav slider-nav-next"></button>
	
	<ul class="slider-indicators"> <?php
foreach ($attributes["images"] as $image)
{ ?>
   
        <li class=""></li>

	<?php
} ?>
	       
    </ul>
	
</div>

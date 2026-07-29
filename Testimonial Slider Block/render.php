<?php

$htmlContent = $content;
$dom = new DOMDocument();
$dom->loadHTML($htmlContent);
$xpath = new DOMXPath($dom);
$query = '//div[contains(@class, \'testimonial-group\')]';
$divs = $xpath->query($query);
$testimonialGroup = [];

// Loop through the divs and save their content to the array
foreach ($divs as $div) {
    $testimonialGroup[] = $dom->saveHTML($div);
} 
?>

<div class="swiffy-slider slider-nav-dark slider-nav-sm slider-nav-visible slider-nav-touch slider-indicators-round slider-indicators-dark slider-indicators-sm slider-nav-animation">
	<ul class="slider-container" >		
		<?php
		foreach ($testimonialGroup as $value) {
			$key++;
			echo '<li >'.$value.'</li>';
		} ?>       
	</ul>
	<button type="button" class="slider-nav"></button>
    <button type="button" class="slider-nav slider-nav-next"></button>	
    <ul class="slider-indicators">
		<?php
		foreach ($testimonialGroup as $value) {
			echo '<li></li>';
		} ?>
        
    </ul>
	
</div>	

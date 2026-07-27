/** 
* Show more/less in WordPress by WPCookie
* https://redpishi.com/wordpress-tutorials/show-more-wordpress/
*/
add_filter( 'wp_footer', function ( ) {  ?>
<script>
	let fade = "1"  // 0 or 1
	let height = "6rem"  // use px, em or rem
</script>
<style id="show-more-style">
:root {
  --show-more-color: white;
}
.more-content {
	position: relative;
	max-height: 6rem;
	overflow: hidden;
	transition: all 0.7s ease;
}
.more-content:before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, var(--show-more-color) 0%, rgba(256,256,256,0) 35%);
	pointer-events: none;
}
.more-content.open {
	max-height: 1000px;
}
.more-content.open:before {
    content: '';
    visibility: hidden;
} 
.show-more {
	cursor: pointer;
}
</style>
<script>
const moreContent = [...document.querySelectorAll(".more-content")]
const showMore = [...document.querySelectorAll(".show-more")]
showMore?.forEach( btn => btn.addEventListener("click", e => {
	index = showMore.findIndex( h => h == e.target.parentElement  );
	if(moreContent[index].classList.contains("open") ){
		moreContent[index].classList.remove("open");
		e.target.innerHTML = "Show more";
	} else {
		moreContent[index].classList.add("open");
		e.target.innerHTML = "Show less";
	}} ))
let add = "";
if ( fade == 0 ) {
 add = 	".more-content:before { background: #ffffff00; }"
} 
add +=  ".more-content {  max-height: "+height+"; }"
document.querySelector("#show-more-style").innerHTML += add
</script>
<?php });

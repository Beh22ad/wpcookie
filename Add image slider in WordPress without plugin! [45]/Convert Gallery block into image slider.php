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
    slider.insertAdjacentHTML("afterend",main  );
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
  console.log("prevSlide is called");
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
 } )

</script>

<style>
wp-slider * {
padding = 0;
margin = 0;
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
  .wp-slider{width:100%;
  }  
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

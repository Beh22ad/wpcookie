/** 
* FAQ Schema in WordPress by WPCookie
* https://redpishi.com/wordpress-tutorials/faq-schema-in-wordpress/
*/
add_filter( 'wp_footer', function ( ) {  ?>
<script>
let question = []
document.querySelectorAll(".question").forEach( e => question.push(e.innerText) )
let answer = []
document.querySelectorAll(".answer").forEach( e => answer.push(e.innerText) )
let faqInside = []
if ( question.length > 0 ) {
for (let i = 0; i < question.length; i++) {
faqInside.push(`{"@type":"Question","name":"${question[i]}","acceptedAnswer":{"@type":"Answer","text":"${answer[i]}"}}`)
}
let faq = String(faqInside)
let faqFinal = `{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[${faq}]}`
var scriptschemafaq = document.createElement("script");
scriptschemafaq.type = "application/ld+json";
scriptschemafaq.innerHTML = faqFinal;
document.querySelector('footer').appendChild(scriptschemafaq);	
}
</script>
<?php });

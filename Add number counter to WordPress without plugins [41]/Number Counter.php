/** 
* Number Counter in WordPress by WPCookie
* https://redpishi.com/wordpress-tutorials/number-counter/
*/
add_filter( 'wp_footer', function ( ) {  ?>
<script>
const counters = document.querySelectorAll('.counter');
if (counters) {
	const observer = new IntersectionObserver(entries => {
    entries.forEach( entry => {
      if ( entry.isIntersecting ) { 
      const finalNumber =  entry.target.innerText;
      const time = Math.ceil(finalNumber / 30) ;
      entry.target.innerText = 0;
      const animate = () => {
	      const data = +entry.target.innerText;			   
        if(data < finalNumber) { 
            entry.target.innerText = Math.ceil(data + time);
            setTimeout(animate, 50);
          }else{
            entry.target.innerText = finalNumber;
          }	 
	      }
      animate(); 
      observer.unobserve(entry.target);
      }
    })
    
  }, {threshold: 0.1 })

  counters.forEach( counter => {
    observer.observe(counter)	
  });
}
</script>
<?php });

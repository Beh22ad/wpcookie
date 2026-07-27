/*
 * Image Lightbox by WPCookie
 * https://redpishi.com/wordpress-tutorials/lightbox-wordpress/
 * */
add_action( 'wp_footer', function(){ if(!is_admin()) { ?>
<style>
img {
  display: block;
  max-width: 100%;
}
.lightbox {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 999999;
  background-color: rgba(0, 0, 0, 0.75);
  display: flex;
  justify-content: center;
  align-items: center;
}
.lightbox-content {
  width: 1000px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 25px;
  user-select: none;
}
.lightbox-content img {
  max-height: 600px;
  width: 90%;
  object-fit: cover;
  border-radius: 4px;
}
.lightbox-content i {
  color: white;
  font-size: 60px;
  cursor: pointer;
  flex-shrink: 0;
}
i.lightbox-prev, i.lightbox-next {
    padding: 30px 5px;
}
svg.lightbox-prev, svg.lightbox-next {
    pointer-events: none;
}
</style>
<script>
window.addEventListener('load', function () {

[...document.querySelectorAll('main figure a')].forEach(e => {if (e.href.split("/").at(-1).includes('.')) e.replaceWith(...e.childNodes); })
const Allimages = [...document.querySelectorAll('body img')]
const images = Allimages.filter( e => { return e.clientHeight > 150  } )
images.forEach(e => e.style.cursor = "zoom-in")

  array = [...images].forEach(item => item.addEventListener('click', handleCreateLightbox))
  function handleCreateLightbox(e) {
    const linkImage = e.target.getAttribute('src')
    const code = `<div class="lightbox">
  <div class="lightbox-content">
    <i class="lightbox-prev"><svg class="lightbox-prev" width="22"  viewBox="0 0 66.692 126.253" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><g style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1"><path d="m360.731 229.075-225.1-225.1c-5.3-5.3-13.8-5.3-19.1 0-5.3 5.3-5.3 13.8 0 19.1l215.5 215.5-215.5 215.5c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4 3.4 0 6.9-1.3 9.5-4l225.1-225.1c5.3-5.2 5.3-13.8.1-19z" style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1" transform="matrix(-.26458 0 0 .26458 96.472 0)"/></g></svg></i>
    <img
      src="${linkImage}"
      alt=""
      class="lightbox-image"
    />
    <i class="lightbox-next"><svg class="lightbox-next" width="22" viewBox="0 0 66.692 126.253" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><g style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1"><path d="m360.731 229.075-225.1-225.1c-5.3-5.3-13.8-5.3-19.1 0-5.3 5.3-5.3 13.8 0 19.1l215.5 215.5-215.5 215.5c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4 3.4 0 6.9-1.3 9.5-4l225.1-225.1c5.3-5.2 5.3-13.8.1-19z" style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1" transform="matrix(.26458 0 0 .26458 -29.78 0)"/></g></svg></i>
  </div>
</div>`
    document.body.insertAdjacentHTML('beforeend', code)
  }
  let index = 0
  document.addEventListener('click', handleOutLightbox)
  function handleOutLightbox(e) {
    const lightImage = document.querySelector('.lightbox-image')
    let imageSrc = ''
    if (!lightImage) return

    if (e.target.matches('.lightbox')) {
      const body = e.target.parentNode
      body.removeChild(e.target)

    } else if (e.target.matches('.lightbox-next')) {
      imageSrc = lightImage.getAttribute('src')
      index = [...images].findIndex(item => item.getAttribute('src') === imageSrc)
      index = index + 1
      firstImage = 0
      if (index > images.length - 1) {
        index = firstImage
      }
      ChangeLinkImage(index, lightImage)

    } else if (e.target.matches('.lightbox-prev')) {
      imageSrc = lightImage.getAttribute('src')
      index = [...images].findIndex(item => item.getAttribute('src') === imageSrc)
      index = index - 1
      lastImage = images.length - 1
      if (index < 0) {
        index = lastImage
      }
      ChangeLinkImage(index, lightImage)
    }
  }
  function ChangeLinkImage(index, lightImage) {
    const newLink = [...images][index].getAttribute('src')
    lightImage.setAttribute('src', newLink)
  }
}) 
</script>

<?php } } );


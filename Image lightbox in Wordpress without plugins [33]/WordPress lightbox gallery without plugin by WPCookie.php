/*
 * WordPress lightbox gallery without plugin by WPCookie
 * https://redpishi.com/wordpress-tutorials/lightbox-wordpress/
 * */
add_action( 'wp_footer', function(){ if(!is_admin()) { ?>
<style>
img {
  display: block;
  max-width: 100%;
}
.cookie_box {
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
.cookie_box-content {
    width: 1000px;
    margin: 0 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    user-select: none;
}
.cookie_box-content img {
  max-height: 600px;
  width: 90%;
  object-fit: cover;
  border-radius: 4px;
}
.cookie_box-content i {
  color: white;
  font-size: 60px;
  cursor: pointer;
  flex-shrink: 0;
}
i.cookie_box-prev, i.cookie_box-next {
    padding: 30px 5px;
}
svg.cookie_box-prev, svg.cookie_box-next {
    pointer-events: none;
}
</style>
<script>
window.addEventListener('load', function () {
const allGallery = [...document.querySelectorAll('.lightbox')]

let imageGroup = [];
let images = [];
allGallery.forEach((item, index) => {
   let imgs =  item.querySelectorAll('img')
    const imageArray = [];
   imgs.forEach((im) => {
        im.setAttribute('data-galley', index);
        imageArray.push(im);
        images.push(im);
})

imageGroup.push(imageArray);


});

images.forEach(e => e.style.cursor = "zoom-in")

  array = [...images].forEach(item => item.addEventListener('click', handleCreateLightbox))
  function handleCreateLightbox(e) {
    let linkImage = e.target.getAttribute('src')
    let ss = e.target.dataset.galley
    let code = `<div class="cookie_box">
  <div class="cookie_box-content">
    <i class="cookie_box-prev" data-source="${ss}"><svg class="cookie_box-prev" data-source="${ss}" width="22"  viewBox="0 0 66.692 126.253" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><g style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1"><path d="m360.731 229.075-225.1-225.1c-5.3-5.3-13.8-5.3-19.1 0-5.3 5.3-5.3 13.8 0 19.1l215.5 215.5-215.5 215.5c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4 3.4 0 6.9-1.3 9.5-4l225.1-225.1c5.3-5.2 5.3-13.8.1-19z" style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1" transform="matrix(-.26458 0 0 .26458 96.472 0)"/></g></svg></i>
    <img
      src="${linkImage}"
      alt=""
      class="cookie_box-image"
    />
    <i class="cookie_box-next" data-source="${ss}"><svg data-source="${ss}" class="cookie_box-next" width="22" viewBox="0 0 66.692 126.253" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><g style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1"><path d="m360.731 229.075-225.1-225.1c-5.3-5.3-13.8-5.3-19.1 0-5.3 5.3-5.3 13.8 0 19.1l215.5 215.5-215.5 215.5c-5.3 5.3-5.3 13.8 0 19.1 2.6 2.6 6.1 4 9.5 4 3.4 0 6.9-1.3 9.5-4l225.1-225.1c5.3-5.2 5.3-13.8.1-19z" style="fill:#fff;fill-opacity:1;stroke:none;stroke-opacity:1" transform="matrix(.26458 0 0 .26458 -29.78 0)"/></g></svg></i>
  </div>
</div>`
    document.body.insertAdjacentHTML('beforeend', code)
  }
  let index = 0
  document.addEventListener('click', handleOutLightbox)
  function handleOutLightbox(e) {
    const lightImage = document.querySelector('.cookie_box-image')
    let imageSrc = ''
    if (!lightImage) return
    let galley_id = e.target.dataset.source;

    if (e.target.matches('.cookie_box')) {
      const body = e.target.parentNode
      body.removeChild(e.target)

    } else if (e.target.matches('.cookie_box-next')) {
      imageSrc = lightImage.getAttribute('src')

      index = imageGroup[galley_id].findIndex(item => item.getAttribute('src') === imageSrc)
      index = index + 1
      firstImage = 0
      if (index > imageGroup[galley_id].length - 1) {
        index = firstImage
      }
      ChangeLinkImage(galley_id , index, lightImage)

    } else if (e.target.matches('.cookie_box-prev')) {
      imageSrc = lightImage.getAttribute('src')
      index = imageGroup[galley_id].findIndex(item => item.getAttribute('src') === imageSrc)
      index = index - 1
      lastImage =imageGroup[galley_id].length - 1
      if (index < 0) {
        index = lastImage
      }
      ChangeLinkImage( galley_id, index, lightImage)
    }
  }
  function ChangeLinkImage(galley_id, index, lightImage) {
    const newLink = imageGroup[galley_id][index].getAttribute('src')
    lightImage.setAttribute('src', newLink)
  }



})
</script>

<?php } } );


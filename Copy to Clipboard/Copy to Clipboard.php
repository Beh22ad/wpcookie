
/* Copy to Clipboard Shortcode for WordPress by WPCookie
 * Register [copy text="…"]
 * update: https://redpishi.com/wordpress-tutorials/copy-to-clipboard/
 */ 
add_shortcode( 'copy', 'wp_copy_shortcode' );
function wp_copy_shortcode( $atts ) {
    static $assets_printed = false;

    // grab & sanitize
    $atts = shortcode_atts( array( 'text' => '' ), $atts, 'copy' );
    $text = esc_html( $atts['text'] );

    $out = '';
    if ( ! $assets_printed ) {
        $out .= <<<HTML
<style>
.copy-container {
  display: inline-flex;
  align-items: center;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 4px 8px;
  background: #fff;
  font-family: sans-serif;
  position: relative;
}
.copy-text {
  margin-right: 8px;
}
.copy-button {
  position: relative;
  background: transparent;
  border: none;
  padding: 4px;
  cursor: pointer;
  border-radius: 4px;
  display: flex;
  align-items: center;
}
.copy-button .icon-check {
  display: none;
}
.copy-button.copied .icon-copy {
  display: none;
}
.copy-button.copied .icon-check {
  display: block;
}
.copy-button svg {
  width: 16px;
  height: 16px;
  fill: #555;
  transition: fill .2s;
}
.copy-button:hover .tooltip {
  opacity: 1;
  visibility: visible;
}
.tooltip {
  position: absolute;
  top: -30px;
  left: 50%;
  transform: translateX(-50%);
  background: #333;
  color: #fff;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  opacity: 0;
  visibility: hidden;
  transition: opacity .2s;
  white-space: nowrap;
  pointer-events: none;
}
button.copy-button:hover, button.copy-button:focus {
    background-color: #e9e9e9;
}
button.copy-button {
    transition: all 0.4s ease;
}
</style>

<script>
(function(){
  document.addEventListener('click', function(e){
    var btn = e.target.closest('.copy-button');
    if (!btn) return;
    var container = btn.closest('.copy-container');
    var txt = container.querySelector('.copy-text').textContent;
    navigator.clipboard.writeText(txt).then(function(){
      btn.classList.add('copied');
      btn.querySelector('.tooltip').textContent = 'Copied!';
      setTimeout(function(){
        btn.classList.remove('copied');
        btn.querySelector('.tooltip').textContent = 'Click to copy';
      }, 2000);
    });
  });
})();
</script>
HTML;
        $assets_printed = true;
    }

    // unique wrapper
    $uid = 'copy-' . uniqid();

    // markup uses both icons
    $out .= <<<HTML
<span id="{$uid}" class="copy-container">
  <span class="copy-text">{$text}</span>
  <button type="button" class="copy-button" aria-label="Copy">
    <!-- clipboard icon -->
    <svg class="icon-copy" viewBox="0 0 24 24">
      <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1Z"/>
      <path d="M20 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H20C21.1 23 22 22.1 22 21V7C22 5.9 21.1 5 20 5ZM20 21H8V7H20V21Z"/>
    </svg>
    <!-- check‑mark icon -->
    <svg class="icon-check" viewBox="0 0 24 24">
      <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/>
    </svg>
    <div class="tooltip">Click to copy</div>
  </button>
</span>
HTML;

    return $out;
}


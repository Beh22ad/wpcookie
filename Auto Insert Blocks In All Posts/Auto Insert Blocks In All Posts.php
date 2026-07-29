/**
 * Auto Insert Block In All Posts code by WPCookie
 * https://redpishi.com/wordpress-tutorials/auto-insert-content-in-all-posts/
 */
function add_message_after_third_block($block_content, $block) {
    $pattern_id = "000";
	$blocks = 5;
		
	static $block_count = 0;
    if ($block['blockName'] && $block_count < $blocks + 1 ) {
        $block_count++;
        if ($block_count === $blocks) {
            $message = get_post_field('post_content', $pattern_id);
            return $block_content . $message;
        }
    }
    return $block_content;
}
function add_filter_for_message($post) {
    if (is_singular('post') && in_the_loop()) {
        add_filter('render_block', 'add_message_after_third_block', 10, 2);
    }
}
add_action('the_post', 'add_filter_for_message');

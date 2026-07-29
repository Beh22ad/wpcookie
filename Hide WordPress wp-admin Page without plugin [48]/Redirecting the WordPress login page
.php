add_action('init', 'prevent_wp_login');
function prevent_wp_login() {
    global $pagenow;
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
        $page = get_bloginfo('url');
        wp_redirect($page);
        exit();
    }
}

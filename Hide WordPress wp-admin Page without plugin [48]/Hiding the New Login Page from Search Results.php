add_filter('pre_get_posts',function($query){
	if (is_admin()) { return $query;  }
	if ($query->is_search) {
		$query->set( 'post__not_in', array( 0000000 ) );
	}
	return $query;
});


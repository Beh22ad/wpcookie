
/*
* [table_of_contents]
* @update: https://redpishi.com/wordpress-tutorials/table-of-contents-in-wordpress/
*/

      
add_action( 'the_content', function($content){
	
	/*****************************
	*	Choose where to display:
	*	0 = only shortcode
	*	1 = all posts
	*****************************/	
	$table_of_contents_type = 0; 	
	


	/*****************************
	*	Exclude posts
	*	[1,2,post_id]
	*****************************/	
	$Excluded_posts=[-1];




	/*****************************
	*	Exclude categories
	*	[10,20,cat_id]
	*****************************/	
	$Excluded_categories=[-1];	



	
    if (strpos( $content, '[table_of_contents]') !== false || ( $table_of_contents_type == 1 && !is_single($Excluded_posts) && !in_category($Excluded_categories) )  ) {
        $content = preg_replace_callback( '/(\<h[2-6](.*?))\>(.*)(<\/h[2-6]>)/i', function( $matches ) {
            if ( ! stripos( $matches[0], 'id=' ) ) :
            $matches[0] = $matches[1] . $matches[2] . ' id="' . sanitize_title( $matches[3] ) . '">' . $matches[3] . $matches[4];
            endif;
            return $matches[0];
        }, $content );
        

        $style = '<style>html { scroll-behavior: smooth; scroll-padding-top: 5em; .table_of_contents a { text-decoration: none; }}</style>';
        if 	( $table_of_contents_type == 1 ) 
        {
			$content = generateIndex($content)['index'].$content;
		} else
		{
			$content = str_replace("[table_of_contents]",generateIndex($content)['index'],$content);
		}
        
        return $style.$content;
    } else return $content;
    
});

function generateIndex($html) {
	preg_match_all('/<h([2-6])*[^>]*>(.*?)<\/h[2-6]>/',$html,$matches);
	$index = "<div class='table_of_contents'><ul>";
	$prev = 2;
	foreach ($matches[0] as $i => $match){
		$curr = $matches[1][$i];
		$text = strip_tags($matches[2][$i]);
		$slug = sanitize_title($text);
		$anchor = '<span id="'.$slug.'">'.$text.'</span>';
		$html = str_replace($text,$anchor,$html);
		$prev <= $curr ?: $index .= str_repeat('</ul>',($prev - $curr));
		$prev >= $curr ?: $index .= "<ul>";
		$index .= '<li><a href="#'.$slug.'">'.$text.'</a></li>';
		$prev = $curr;
	}
	$index .= "</ul></div>";
return ["html" => $html, "index" => $index];	}	


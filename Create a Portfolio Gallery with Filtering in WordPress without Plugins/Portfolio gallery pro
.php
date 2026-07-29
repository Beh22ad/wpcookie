/*
 * [wpcookie-Portfolio description="1" cat="all"]
 * Portfolio gallery with filtering by WPCookie (Pro Version)
 * https://redpishi.com/wordpress-tutorials/portfolio-gallery-with-filtering-in-wordpress/
 */

add_shortcode( 'wpcookie-Portfolio', 'wpcookie_Portfolio_func' );
function wpcookie_Portfolio_func($atts) {
	
	 $atts = shortcode_atts( array(
		 'description' => '1',
		 'cat' => 'all',
		 
    ), $atts, 'wpcookie-Portfolio' );
	static $Portfolio_call = 1;
	$description = $atts["description"];
	$cat = $atts["cat"];
	if ( $cat == "all" ) {
		$cat = $cat_q_arr = $q_cat_name = "" ;
	} else {
		$cat_q_arr = explode(",", $cat);
		$q_cat_name = 'tax_query';
	}
	
	
	
	$buffer= '<section class="WPCookie-portfolio section portfolio_'.$Portfolio_call.'">
  <div class="container">    
    <div class="filters">
      <ul>';
	
	$catList='<li class="active" data-filter="*">All</li>';
	$terms = get_terms("portfolio_category");
	
	if ( $cat == "" ) {

		foreach ( $terms as $term ) {
			$catList .=  '<li data-filter=".'.$term->slug.'">'.$term->name.'</li> ';		
			}
	} else {		
		foreach ( $cat_q_arr as $term_id ) {
			$term = get_term( $term_id, "portfolio_category" );
			$catList .=  '<li data-filter=".'.$term->slug.'">'.$term->name.'</li> ';
			}
	}		
	


	$buffer .= $catList .'</ul> </div> <div class="filters-content"> <div class="row grid">';
    $q = new WP_Query(array(
        'post_type' => 'portfolio',
        'posts_per_page' => -1,
		$q_cat_name => array(
        array(
            'taxonomy' => 'portfolio_category',
            'field'    => 'term_id',
            'terms'    => $cat_q_arr ,
        )
    ),
		
    ));
	
	if( $q->have_posts() ) :
	
    while ($q->have_posts()) :
        $q->the_post();
		
		$termSlug = "";
		$termsArr = get_the_terms(get_the_ID(), 'portfolio_category');
		foreach ( $termsArr as $term ) {
			$termSlug .= $term->slug . ' ';
		
		}
	
		
		$buffer .= ' <div class="all '.$termSlug.'">
          <div class="item">
            <a href="'.get_permalink( get_the_ID() ).'">    '.get_the_post_thumbnail( get_the_ID(), "post-thumbnail" ).'  </a>        
    ';
		
		if ( $description == 1 ) {
			$buffer .= '<div class="p-inner">
              <h5>'.get_the_title().'</h5>
              <div class="cat">'.get_the_content().'</div>
            </div>';
            
		} else { $buffer .= '<style>.section.WPCookie-portfolio .filters-content .item { --card-shadow : none; }</style>'; }
		
		$buffer .= '</div></div>';

	endwhile;	
	
	endif;

	$buffer.='</div></div></div></section>';
	

	
	add_action("wp_footer", function() use ($Portfolio_call){ 
		if ( $Portfolio_call == 1 ) {
		?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

<script>
var filters = [];
var grid = [];
var iso = [];
</script>	
<style>
.WPCookie-portfolio .row.grid > div {
    max-width: 300px;
    margin-left: 30px;
}
.WPCookie-portfolio.section {
  padding: 30px 0;
  color: #333;
  --card-shadow : 0px 10px 25px -10px #0000001a;
  
}
.WPCookie-portfolio.section .top-side {
  text-align: center;
}
.WPCookie-portfolio.section .top-side .title {
  font-weight: 500;
  font-size: 15px;
  display: inline-block;
}
.WPCookie-portfolio.section .top-side .title:after {
  content: "";
  display: block;
  width: 50%;
  border-bottom: 1px solid #494949;
  margin: 8px auto;
}
.section .top-side h2 {
  font-weight: 700;
}
.section.WPCookie-portfolio .filters {
  text-align: center;
  margin-top: 50px;
}
.section.WPCookie-portfolio .filters ul {
  padding: 0;
}
.section.WPCookie-portfolio .filters ul li {
  list-style: none;
  display: inline-block;
  padding: 20px 30px;
  cursor: pointer;
  position: relative;
}
.section.WPCookie-portfolio .filters ul li:after {
  content: "";
  display: block;
  width: calc(0% - 60px);
  position: absolute;
  height: 2px;
  background: #333;
  transition: width 350ms ease-out;
}
.section.WPCookie-portfolio .filters ul li:hover:after {
  width: calc(100% - 60px);
  transition: width 350ms ease-out;
}
.section.WPCookie-portfolio .filters ul li.active:after {
  width: calc(100% - 60px);
}
.section.WPCookie-portfolio .filters-content {
  margin-top: 50px;
}
.section.WPCookie-portfolio .filters-content .show {
  opacity: 1;
  visibility: visible;
  transition: all 350ms;
}
.section.WPCookie-portfolio .filters-content .hide {
  opacity: 0;
  visibility: hidden;
  transition: all 350ms;
}
.section.WPCookie-portfolio .filters-content .item {
    text-align: center;
    cursor: pointer;
    margin-bottom: 30px;
    box-shadow: var(--card-shadow);
    border-radius: 5px;
}
.section.WPCookie-portfolio .filters-content .item .p-inner {
	padding: 6px 17px 20px 17px;
}
.section.WPCookie-portfolio .filters-content .item .p-inner h5 {
  font-size: 15px;
}
.section.WPCookie-portfolio .filters-content .item .p-inner .cat {
  font-size: 13px;
}
.section.WPCookie-portfolio .filters-content .item img {
  width: 100%;
}



</style>

<?php }  ?>

<script>

filters[<?= $Portfolio_call ?>] = document.querySelectorAll(".WPCookie-portfolio.portfolio_<?= $Portfolio_call ?> .filters ul li");
grid[<?= $Portfolio_call ?>] = document.querySelector(".WPCookie-portfolio.portfolio_<?= $Portfolio_call ?> .grid");


iso[<?= $Portfolio_call ?>] = new Isotope(grid[<?= $Portfolio_call ?>], {
  itemSelector: ".all",
  percentPosition: true,
  masonry: {
    columnWidth: ".all"
  }
});


filters[<?= $Portfolio_call ?>].forEach(function (filter) {
  filter.addEventListener("click", function () {
   
    filters[<?= $Portfolio_call ?>].forEach(function (li) {
      li.classList.remove("active");
    });
  
    this.classList.add("active");

    var data = this.getAttribute("data-filter");
    iso[<?= $Portfolio_call ?>].arrange({
      filter: data
    });
  });
});
document.addEventListener("DOMContentLoaded", function(){
    document.querySelector(".section.WPCookie-portfolio.portfolio_<?= $Portfolio_call ?> .filters ul li").click();
});
</script>	
		
	<?php },10, 1);
	
    wp_reset_postdata();	
	$Portfolio_call = $Portfolio_call + 1;	
    return $buffer;
}

add_action( 'init', 'wpcookie_register_taxonomy' );
function wpcookie_register_taxonomy() {
	$args = [
		'label'  => esc_html__( 'Portfolio Categories', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Portfolio Categories', 'wpcooike_portfolio' ),
			'name'               => esc_html__( 'Portfolio Categories', 'wpcooike_portfolio' ),
			'singular_name'      => esc_html__( 'Portfolio Category', 'wpcooike_portfolio' ),
			'add_new_item'       => esc_html__( 'Add new Portfolio Category', 'wpcooike_portfolio' ),
			'new_item'           => esc_html__( 'New Portfolio Category', 'wpcooike_portfolio' ),
			'view_item'          => esc_html__( 'View Portfolio Category', 'wpcooike_portfolio' ),
			'not_found'          => esc_html__( 'No Portfolio Categories found', 'wpcooike_portfolio' ),
			'not_found_in_trash' => esc_html__( 'No Portfolio Categories found in trash', 'wpcooike_portfolio' ),
			'all_items'          => esc_html__( 'All Portfolio Categories', 'wpcooike_portfolio' ),
		],
		'public'              => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_admin_column'   => true,
		'show_in_rest'        => false,
		'hierarchical'        => true,
		'rewrite'             => array( 'slug' => 'portfolio-category' ),
	];

	register_taxonomy( 'portfolio_category', 'portfolio', $args );
}


add_action( 'init', 'wpcookie_register_post_type' );
function wpcookie_register_post_type() {
	$args = [
		'label'  => esc_html__( 'Portfolio', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Portfolio', 'wpcooike_portfolio' ),
			'name'               => esc_html__( 'Portfolio', 'wpcooike_portfolio' ),
			'singular_name'      => esc_html__( 'item', 'wpcooike_portfolio' ),
			'add_new'            => esc_html__( 'Add item', 'wpcooike_portfolio' ),
			'add_new_item'       => esc_html__( 'Add new item', 'wpcooike_portfolio' ),
			'new_item'           => esc_html__( 'New item', 'wpcooike_portfolio' ),
			'edit_item'          => esc_html__( 'Edit item', 'wpcooike_portfolio' ),
			'view_item'          => esc_html__( 'View item', 'wpcooike_portfolio' ),
			'update_item'        => esc_html__( 'View item', 'wpcooike_portfolio' ),
			'all_items'          => esc_html__( 'All items', 'wpcooike_portfolio' ),
			'search_items'       => esc_html__( 'Search item', 'wpcooike_portfolio' ),
		],
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => false,
		'show_in_rest'        => false,
		'capability_type'     => 'post',
		'hierarchical'        => true,
		'has_archive'         => 'portfolio',
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_position'         => 5,
		'menu_icon'           => 'dashicons-images-alt2',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
		],
		'taxonomies' => [
			'portfolio_category',
		],
		'rewrite' => array( 'slug' => 'portfolio' ),
	];

	register_post_type( 'portfolio', $args );
}

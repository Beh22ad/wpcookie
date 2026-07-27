/* Changing the price of WordPress products dynamically by redpishi.com */
function filtering_product_prices( $price, $product ) {
$price = (float)$price;
$new_price = $price * 1 ;	// New price formula
	
// paste conditional codes here

return ceil($new_price);
}

add_filter('woocommerce_product_get_price', 'custom_price', 90, 2 );
add_filter('woocommerce_product_get_regular_price', 'custom_price', 90, 2 );
add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2 );
add_filter('woocommerce_product_variation_get_price', 'custom_price' , 99, 2 );
add_filter('woocommerce_variation_prices_price', 'custom_variation_price', 99, 3 );
add_filter('woocommerce_variation_prices_regular_price', 'custom_variation_price', 99, 3 );
function custom_price( $price, $product ) {
    wc_delete_product_transients($product->get_id());
	return filtering_product_prices( $price, $product );
}
function custom_variation_price( $price, $variation, $product ) {    
    wc_delete_product_transients($variation->get_id());
	return filtering_product_prices( $price, $product );	
}

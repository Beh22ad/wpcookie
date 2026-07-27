/* buy now button woocommerce code by WPcookie
 * update: https://redpishi.com/wordpress-tutorials/buy-now-button-woocommerce/
 */
add_action('wp_footer', function() {
    if (!is_admin()) {
        // Change button color from here
        $color = "";
        ?>
        <style>
        a.custom-checkout-btn {
            margin: 0px 5px!important;
        }
        </style>
        <?php
        if ($color != "") {
            ?>
            <style>
            a.custom-checkout-btn {
                background: <?php echo $color; ?>!important;
                transition: 0.4s filter ease;
            }
            a.custom-checkout-btn:hover {
                filter: saturate(2);
            }
            </style>
            <?php
        }
    }
});

add_action('woocommerce_after_add_to_cart_button', 'add_custom_addtocart_and_checkout');
function add_custom_addtocart_and_checkout() {
    global $product;

    // conditional tags
    $condition = $product->is_type('simple') || $product->is_type('variable');

    $button_class = 'single_add_to_cart_button button alt custom-checkout-btn';

    // Change the text of the buy now button from here
    $button_text = __("Buy now", "woocommerce");

    if ($condition) {
        // For both simple and variable products
        ?>
        <script>
        jQuery(function($) {
            var checkoutUrl = '<?php echo wc_get_checkout_url(); ?>',
                addToCartUrl = '<?php echo wc_get_cart_url(); ?>',
                qty = 'input.qty',
                button = 'a.custom-checkout-btn';

            function updateBuyNowButton() {
                var $form = $('form.cart'),
                    productId = $form.find('input[name="product_id"]').val(),
                    variationId = $form.find('input[name="variation_id"]').val(),
                    quantity = $form.find(qty).val();

                var targetUrl = checkoutUrl + '?add-to-cart=';
                
                if (variationId && variationId != '') {
                    targetUrl += variationId;
                } else {
                    targetUrl += productId;
                }

                targetUrl += '&quantity=' + quantity;

                // Add all selected attributes to the URL
                $form.find('.variations select').each(function() {
                    var attributeName = $(this).attr('name');
                    var attributeValue = $(this).val();
                    if (attributeValue) {
                        targetUrl += '&' + attributeName + '=' + attributeValue;
                    }
                });
                
                $(button).attr('href', targetUrl);
            }

            // Initial update
            updateBuyNowButton();

            // On quantity change
            $(qty).on('input change', updateBuyNowButton);

            // On variation change
            $('.variations_form').on('woocommerce_variation_has_changed', updateBuyNowButton);

            // Ensure button is updated after variation is selected
            $('form.variations_form').on('show_variation', function(event, variation) {
                updateBuyNowButton();
            });

            // Update on any attribute change
            $('.variations_form').on('change', 'select', updateBuyNowButton);

            // Disable the button if not all attributes are selected
            $('.variations_form').on('change', 'select', function() {
                var allSelected = true;
                $('.variations_form select').each(function() {
                    if ($(this).val() === '') {
                        allSelected = false;
                        return false;  // break the loop
                    }
                });
                
                if (allSelected) {
                    $(button).removeClass('disabled').attr('aria-disabled', 'false');
                } else {
                    $(button).addClass('disabled').attr('aria-disabled', 'true');
                }
            });

            // Prevent click if button is disabled
            $(button).on('click', function(e) {
                if ($(this).hasClass('disabled')) {
                    e.preventDefault();
                    alert('Please select all product options before purchasing.');
                }
            });
        });
        </script>
        <?php
        echo '<a href="#" class="' . $button_class . '">' . $button_text . '</a>';
    }
}

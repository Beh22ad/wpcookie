/*
 * Variation Dropdown to Radio Button Code by wpcookie
 * https://redpishi.com/wordpress-tutorials/dropdown-to-radio-button/
 */	
add_action( 'woocommerce_variable_add_to_cart', function() {
    add_action( 'wp_print_footer_scripts', function() {
		$color = "#1c1c1c";
		
        ?>
        <script type="text/javascript">

        // DOM Loaded
        document.addEventListener( 'DOMContentLoaded', function() {

            // Get Variation Pricing Data
            var variations_form = document.querySelector( 'form.variations_form' );
            var data = variations_form.getAttribute( 'data-product_variations' );
            data = JSON.parse( data );

            // Loop Drop Downs
            document.querySelectorAll( 'table.variations select' )
                .forEach( function( select ) {

                // Loop Drop Down Options
                select.querySelectorAll( 'option' )
                    .forEach( function( option ) {

                    // Skip Empty
                    if( ! option.value ) {
                        return;
                    }

                    // Get Pricing For This Option
                    var pricing = '';
                    data.forEach( function( row ) {
                        if( row.attributes[select.name] == option.value ) {
                            pricing = row.price_html;
                        }
                    } );

					 var span = document.createElement( 'span' );

                    // Create Radio
                    var radio = document.createElement( 'input' );
                        radio.type = 'radio';
                        radio.name = select.name;
                        radio.value = option.value;
                        radio.checked = option.selected;
					radio.setAttribute('id',option.value);
                    var label = document.createElement( 'label' );
					     	 label.htmlFor = option.value;
                    	label.appendChild( document.createTextNode( ' ' + option.text + ' ' ) );

					 span.appendChild( radio );
					 span.appendChild( label );


                    // Insert Radio
                    select.closest( 'td' ).appendChild( span );

                    // Handle Clicking
                    radio.addEventListener( 'click', function( event ) {
                        select.value = radio.value;
                        jQuery( select ).trigger( 'change' );
                    } );

                } ); // End Drop Down Options Loop

                // Hide Drop Down
                select.style.display = 'none';

            } ); // End Drop Downs Loop

        } ); // End Document Loaded
        </script>
<style>
html {
--radio-color: <?= $color ?>;	
}	
td.value {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 10px;
}


td.value input[type="radio"] {
    appearance: none;
    display: none;
}

td.value label {
    font-size: 1em;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: inherit;
    text-align: center;
    border-radius: 5px;
    overflow: hidden;
    transition: linear 0.3s;
    color: var(--radio-color);
    padding: 0.3em 0.6em;
    border: 2px solid var(--radio-color);
    cursor: pointer;
}
td.value input[type="radio"]:checked + label {
    background-color: var(--radio-color);
    color: #f1f3f5;
    transition: 0.3s;
}
a.reset_variations {
    display: none!important;
}
</style>
        <?php
    } );
} );

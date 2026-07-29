/**
 * Plugin Name: WooCommerce Google Sheets Integration
 * Description: Sends order data to Google Sheets whenever an order is created or updated.
 * Version: 1.1
 * Author: WPCookie | Maya1535 {at} gmail.com
 */

add_action('woocommerce_checkout_order_processed', 'send_order_to_google_sheets', 10, 1);
add_action('woocommerce_order_status_changed', 'send_order_to_google_sheets', 10, 4);

function send_order_to_google_sheets($order_id, $old_status = '', $new_status = '', $order = null) {

    $url = 'https://script.google.com/macros/s/00000000000000000000000000000/exec';

    if (!$order) {
        $order = wc_get_order($order_id);
    }
    if (!$order) {
        error_log('Invalid order ID: ' . $order_id);
        return;
    }
    $order_data = array(
        'id'       => $order->get_id(),
        'status'   => $order->get_status(),
        'name'     => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
        'phone'    => $order->get_billing_phone(),
        'billing'  => implode(', ', array_filter(array(
            $order->get_billing_address_1(),
            $order->get_billing_address_2(),
            $order->get_billing_city(),
            $order->get_billing_state(),
            $order->get_billing_postcode(),
            $order->get_billing_country(),
            $order->get_billing_email()
        ))) ,
        'products' => implode(', ', array_map(function($item) {
            return $item->get_name();
        }, $order->get_items())),
        'total'    => $order->get_total(),
        'date'     => $order->get_date_created() ? $order->get_date_created()->date('Y-m-d') : ''
    );
    $data_json = wp_json_encode($order_data);
    $args = array(
        'body'    => $data_json,
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
    );
    $response = wp_remote_post($url, $args);
    if (is_wp_error($response)) {
        error_log('Error sending order data to Google Sheets: ' . $response->get_error_message());
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            error_log('Unexpected response code: ' . $response_code . '. Response: ' . wp_remote_retrieve_body($response));
        }
    }
}


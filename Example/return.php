<?php

require_once "vendor/autoload.php";

use Wollito\Wollito\Wollito;

$api_key = "YOUR_KEY_HERE";
$api_secret = "YOUR_SECRET_KEY_HERE";
$statement_descriptor = "STATEMENT_DESCRIPTOR"; // Update with your desired statement descriptor
$site_url = "YOUR_URL"; // Update with your site's URL
$currency = "GBP";

$wollito = new Wollito($api_key, $api_secret, $statement_descriptor, $site_url, $currency);

if (isset($_GET['hash'])) {
    $token = $_GET['hash'];
    $check = $wollito->check_token($token);

    // Process the payment status returned by the check_token method
    if ($check === "success") {
        // Payment was successful, perform necessary actions here
        // You can update your order status, send notifications, etc.
        // Example: update_order_status($order_id, "paid");
        echo "Payment was successful!";
    } elseif ($check === "pending") {
        // Payment is pending, handle accordingly
        echo "Payment is pending. We will notify you once it's completed.";
    } elseif ($check === "failed") {
        // Payment failed, handle accordingly
        echo "Payment failed. Please try again or contact support.";
    } else {
        // Invalid status, handle error or log it
        // You may want to log unexpected status values for debugging
        echo "Unexpected payment status: " . $check;
    }
} else {
    // Handle the case when 'hash' parameter is missing in the URL
    echo "Invalid return URL. 'hash' parameter is missing.";
}
?>

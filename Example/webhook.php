<?php
require_once "vendor/autoload.php";

use Wollito\Wollito\Wollito;

$api_key = "YOUR_KEY_HERE";
$api_secret = "YOUR_SECRET_KEY_HERE";
$currency = "GBP";

$wollito = new Wollito($api_key, $api_secret, "", $currency);

try {
    // Validate the webhook call using the provided secret key
    $wollito->validate_webhook_call($_POST);

    // Retrieve the payload data
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Handle the payment status update
    if ($status === "success") {
        // Payment was successful, perform necessary actions here
        // You can update your order status, send notifications, etc.
        // Example: update_order_status($order_id, "paid");
    } elseif ($status === "pending") {
        // Payment is pending, handle accordingly
    } elseif ($status === "failed") {
        // Payment failed, handle accordingly
    } else {
        // Invalid status, handle error or log it
        // You may want to log unexpected status values for debugging
    }

    // Send a response to acknowledge the webhook
    http_response_code(200);
    echo "Webhook received and processed successfully.";
} catch (Exception $e) {
    // Handle any exceptions that occur during webhook processing
    http_response_code(500);
    echo "Webhook processing failed: " . $e->getMessage();
}
?>

<?php

use Wollito\Wollito\Wollito;

require_once 'vendor/autoload.php';

$wollito = new Wollito("YOUR_KEY_HERE", "YOUR_SECRET_KEY_HERE",  "YOUR_URL_HERE" , "GBP");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $cname = $_POST['cname'];
    $country = $_POST['country'];
    $line1 = $_POST['line1'];
    $line2 = $_POST['line2'];
    $city = $_POST['city'];
    $state_country = $_POST['state_country'];
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $currency = $_POST['currency'];
    $order_id = $_POST['order_id'];
    $id_key = $_POST['id_key'];

    // Perform server-side validation here for all the form fields.

    $link = $wollito->create_charge_link("1.00", "123", "http://127.0.0.1/wollito_v2/return.php?payment=true");
    if ($link) {
        header("Location: " . $link);
        exit; // Ensure no further code execution after redirection.
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wollito Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h3>Pay with Wollito</h3>
    <form method="POST" id="frmPayment">
        <!-- Add your form fields here -->
        <!-- For example: -->
        <div class="form-group">
            <label>First name *</label>
            <input required type="text" class="form-control" id="fname" name="fname" value="">
        </div>
        <div class="form-group">
            <label>Last name *</label>
            <input required type="text" class="form-control" id="lname" name="lname" value=""><br>
        </div>
        <!-- Add more form fields as needed -->

        <!-- Hidden fields for currency, order_id, and id_key -->
        <input type="hidden" id="currency" name="currency" value="USD">
        <input type="hidden" id="order_id" name="order_id" value="1234">
        <input type="hidden" id="id_key" name="id_key" value="<?php echo base64_encode(openssl_random_pseudo_bytes(16)); ?>">

        <button type="submit" class="btn btn-primary">Pay Now</button>
    </form>
</div>

</body>
</html>

<?php
require_once "vendor/autoload.php";
$wollito = new \WollitoPackage\Wollito("YOUR KEY HERE", "YOUR SECRET KEY HERE", "GBP");


//Returns true or throws exception
$wollito->validate_webhook_call($_POST);

//PAYLOAD INCLUDES SECRET KEY, ORDER ID & STATUS
//secret, order_id, status

//status include success, pending, failed
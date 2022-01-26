<?php

require_once "vendor/autoload.php";
$wollito = new \WollitoPackage\Wollito("YOUR KEY HERE", "YOUR SECRET KEY HERE", "STATEMENT DESCRIPTOR", "YOUR URL", "GBP");

if(isset($_GET['hash'])){
    $check = $wollito->check_token($_GET['hash']);
    print_r($check);
    //CHECK RETURNS success, pending or failed

    //YOU CAN NOW PROCESS THE RESULT AS YOU PLEASE

}
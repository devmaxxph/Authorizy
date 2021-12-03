<?php

namespace WollitoPackage;
use Exception;


class Wollito
{
    private $accepted_currencies = ["USD", "GBP", "EUR"];
    private $api_key = "";
    private $api_secret = "";
    private $currency = "GBP";
    private $url = "https://securepayment.wollito.com/temp/charge.php";
    private $address_info = [];

    public function __construct($api_key = "", $api_secret = "", $currency = "GBP")
    {
        $this->set_keys($api_key, $api_secret);
        $this->set_currency($currency);
    }


    public function set_keys($api_key, $api_secret)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    public function set_currency($currency){
        if($this->validate_currency($currency)){
            $this->currency = $currency;
        }
    }

    public function set_address_info($line1, $line2, $city, $state, $post_code, $country)
    {
        $this->address_info = [
            "line1"=>  $line1,
            "line2"=>$line2,
            "city"=>$city,
            "state"=>$state,
            "postal_code"=>$post_code,
            "country"=>$country
        ];
    }

    public function process_payment($amount, $card_number, $exp_month, $exp_year, $cvc, $order_id, $id_key){
        $this->check_keys();
        //add amount validation & luhn check
        $postfields = [
            'card'     => $card_number,
            'exp_month' => $exp_month,
            'exp_year'    => $exp_year,
            'cvc'  => $cvc,
            'amount' => $amount,
            'currency' => $this->currency,
            'order_id' => $order_id,
            'key' => $this->api_key,
            'secret' => $this->api_key,
            'site_url' => $this->url(),
            'id_key' => $id_key,
            'type' => 'php'
        ];

        if($this->address_info){
            $postfields["line1"] = $this->address_info['line1'];
            $postfields["line2"] = $this->address_info['line2'];
            $postfields["city"] = $this->address_info['city'];
            $postfields["state"] = $this->address_info['state'];
            $postfields["postal_code"] = $this->address_info['postal_code'];
            $postfields["country"] = $this->address_info['country'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
        $response = curl_exec($ch);

        if (curl_error($ch)) {
            $this->return_error("Could not make API call. Unknown error occurred.");

        }
        curl_close($ch);
        return $response;
    }

    public function validate_webhook_call($secret){
        if(isset($secret['secret'])){
            if($this->api_secret == $secret['secret']){
                return true;
            }
        }
        $this->return_error("Could not be authenticated");

    }

    private function check_keys(){
        if($this->api_key && $this->api_secret){
            return true;
        }else{
            $this->return_error("Public and Secret key cannot be null.");
        }
    }

    private function validate_currency($currency){
        foreach ($this->accepted_currencies as $accept){
            if(strtoupper($currency) == $accept){
                return true;
            }
        }
        $this->return_error("Currency not accepted. Please choose one of the following; GBP, USD, EUR.");
    }

    private function return_error($message){
        throw new Exception($message);
    }

    private function url(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}

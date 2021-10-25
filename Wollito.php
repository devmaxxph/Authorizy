<?php

namespace WollitoPackage;
use Exception;


class Wollito
{
    private $accepted_currencies = ["USD", "GBP", "EUR"];
    private $api_key = "";
    private $api_secret = "";
    private $currency = "GBP";
    private $url = "https://e554-82-23-186-246.ngrok.io/wolito_wp_api/charge.php";

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

    public function process_payment($amount, $card_number, $exp_month, $exp_year, $cvc, $order_id){
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
            'site_url' => $_SERVER['REQUEST_URI'],
            'type' => 'php'
        ];

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
}

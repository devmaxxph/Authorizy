<?php

namespace wollito\Wollito;
class Wollito
{
    private $accepted_currencies = ["USD", "GBP", "EUR"];
    private $api_key = "";
    private $api_secret = "";
    private $currency = "GBP";
    private $descriptor = "";
    private $site_url = "";
    private $url = "https://paymentgateway.wollito.com/";


    public function __construct($api_key = "", $api_secret = "", $site_url = "", $currency = "GBP")
    {
        $this->set_keys($api_key, $api_secret);
        $this->set_currency($currency);
        $this->set_site_url($site_url);
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

    public function set_descriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    public function set_site_url($url)
    {
        $this->site_url = $url;
    }

    public function create_charge_link($amount, $order_id, $return_url)
    {
        $this->check_keys();

        $currency = $this->currency;
        $email = "a@gmail.com";
        $site = $this->site_url;
        $product = $order_id;
        $publishable_key = $this->api_key;
        $private_key = $this->api_secret;
        $type = "PHP";
        $d = base64_encode($currency . "***" . $amount . "***" . $order_id . "***" . $email . "***" . $site . "***" . $product. "***" . $publishable_key. "***" . $private_key. "***" .$type);

        if(strpos($return_url, "http") === false){
            $this->return_error("Invalid return url");
        }

        return "https://paymentgateway.wollito.com/3d.php?d=".$d."&redirect=".$return_url;
    }

    public function check_token($token){
        $res = file_get_contents("https://paymentgateway.wollito.com/token_check.php?token=".$token);
        if($res == "success"){
            return "success";
        }else if($res == "pending"){
            return "pending";
        }
        return "failed";
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

    public function validate_webhook_call($secret){
        if(isset($secret['secret'])){
            if($this->api_secret == $secret['secret']){
                return true;
            }
        }
        $this->return_error("Could not be authenticated");

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

<?php

namespace SimpleSMS;

require_once 'exceptions.php';

class SimpleSMS
{
    public $development_mode    = FALSE;
    public $access_key          = "";
    public $secret_key          = "";
    public $session             = null;
    public $sender_id           = "";

    function __construct($access_key = "", $secret_key = "")
    {
        if(!extension_loaded('curl'))
        {
            throw new Exception("SimpleSMS library requires CURL. Please install PHP Curl extension", 1);
        }
        $this->access_key = $access_key;
        $this->secret_key = $secret_key;
        $this->__init();
    }

    function __init()
    {
        $this->session = curl_init();
        curl_setopt( $this->session, CURLOPT_COOKIEJAR, 'cookiejar' );
        curl_setopt( $this->session, CURLOPT_COOKIEFILE, 'cookiejar' );
        curl_setopt( $this->session, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt( $this->session, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $this->session, CURLOPT_SSL_VERIFYPEER, true );
        curl_setopt( $this->session, CURLOPT_CAINFO, dirname(__FILE__).'/cert/ca-bundle.crt');
        curl_setopt( $this->session, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $this->session, CURLOPT_RETURNTRANSFER, 1 );
        $this->initialized = TRUE;
    }

    public function base_url()
    {
        if($this->development_mode)
        {            
            return 'https://api-sandbox.simplesms.id/v1';
        }
        else
        {
            return 'https://api.simplesms.id/v1';
        }
    }

    public function getApiTimestamp()
    {
        return date('c');
    }

    public function getApiSignature($httpverb, $url, $timestamp)
    {
        $path       = parse_url($url, PHP_URL_PATH);
        $message    = 'GET'.$path.$timestamp;
        $digest     = hash_hmac('sha256', $message, $this->secret_key);
        $signature  = base64_encode($digest);
        return $signature;
    }

    public function getApiHeaders($httpverb, $url)
    {
        $timestamp = $this->getApiTimestamp();
        $headers = array
        (
            'Accept: application/json',
            'X-API-AccessKey: '.$this->access_key,
            'X-API-Timestamp: '.$timestamp,
            'Authorization: '.$this->getApiSignature('GET', $url, $timestamp),
            'Connection: close'
        );
        return $headers;
    }

    public function parseResponse($response)
    {
        $json = json_decode($response, true);        
        if(json_last_error() != JSON_ERROR_NONE)
        {
            throw new InvalidResponseException(json_last_error_msg(), json_last_error());
        }
        elseif($json != null)
        {
            if(array_key_exists('error', $json))
            {
                throw new InvalidRequestException($json['error_msg'], $json['error_code']);
            }
        }
        return $json;
    }

    function _get($url)
    {
        $headers = $this->getApiHeaders('GET', $url);
        curl_setopt( $this->session, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $this->session, CURLOPT_URL, $url );
        curl_setopt( $this->session, CURLOPT_HTTPGET, 1);
        $output = curl_exec( $this->session );
        return $output;
    }

    function _post($url, $params)
    {
        $headers = $this->getApiHeaders('POST', $url);
        curl_setopt( $this->session, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $this->session, CURLOPT_FOLLOWLOCATION, 0 );
        curl_setopt( $this->session, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $this->session, CURLOPT_URL, $url );
        if (count($params) > 0)
        {
            curl_setopt( $this->session, CURLOPT_POSTFIELDS, http_build_query($params) );
        }
        curl_setopt( $this->session, CURLOPT_POST, 1 );
        $output = curl_exec( $this->session );
        return $output;
    }

    public function getBalance()
    {
        $url = $this->base_url().'/account/balance';
        $response = $this->_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function send($msisdn, $text)
    {
        $params = array('msisdn' => $msisdn, 'text' => $text, 'senderid' => $this->sender_id);
        $url = $this->base_url().'/send';
        $response = $this->_post($url, $params);
        $data = $this->parseResponse($response);
        return $data;        
    }

    public function broadcast($recipients, $text)
    {
        $params = array('recipients' => $recipients, 'text' => $text, 'senderid' => $this->sender_id);
        $url = $this->base_url().'/broadcast';
        $response = $this->_post($url, $params);
        $data = $this->parseResponse($response);
        return $data;      
    }

}

?>
<?php

namespace Dalsn\Cashworx;

use Exceptions\CashworxException;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;

/**
 * Class Client
 *
 * The main class for API consumption
 *
 * @package Dalsn\Cashworx
 */
class Cashworx
{
    /** @var string The instance token, settable once per new instance */
    private $instanceToken;
    private $time;
    public $baseUrl = 'https://cashworxportal.com/api/';

    /**
     * @param string
     * @throws CashworxException When no token is provided
     */
    public function __construct($access_key, $access_secret, $baseUrl=null)
    {
        if($baseUrl) {
            if ($baseUrl[strlen($baseUrl) - 1] != '/')
                $baseUrl .= '/';
            $this->baseUrl = $baseUrl;
        }

        $this->instanceToken = $this->getToken($access_key, $access_secret);

        if ($this->instanceToken)
            $this->time = Carbon::now('Africa/Lagos');
    }

    private function getToken($key, $secret)
    {
        $url = "authenticate";

        $credentials = [
            'access_key' => $key,
            'access_secret' => $secret
        ];

        $client = new GuzzleClient([
            'base_uri' => $this->baseUrl,
        ]);

        $response = $this->response($client->post($url, ['form_params' => $credentials]));

        return $response['data']['access_token'];
    }

    private function response($response)
    {
        return json_decode($response->getBody(), true);
    }

    private function request($method, $url, $data = [])
    {
        $this->validateToken();

        $client = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer " . $this->instanceToken,
                'Content-Type' => "application/json"
            ]
        ]);

        return $this->response($client->$method($url, $data));
    }

    private function refreshToken()
    {
        $url = "refresh";
        $client = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer " . $this->instanceToken,
                'Content-Type' => "application/json"
            ]
        ]);

        $response = $this->response($client->post($url, []));

        if (isset($response['data']['access_token']))
            $this->instanceToken = $response['data']['access_token'];
        else
            throw new CashworxException();
    }

    private function validateToken()
    {
        if ($this->time->diffInHours(Carbon::now('Africa/Lagos')) >= 1)
            $this->refreshToken();
    }

    /**
     * [createInvoice Create an invoice]
     * @param  [array] $invoice_data
     * @return [object]
    */
    public function createInvoice(array $invoice_data)
    {
        $url = "invoices";

        return $this->request('post', $url, ['form_params' => $invoice_data]);
    }

    /**
     * [getInvoices Get all invoices]
     * @return [array]
    */
    public function getInvoices()
    {
        $url = "invoices";

        return $this->request('get', $url);
    }

    /**
     * [getInvoice Get an invoice]
     * @param  [string] $invoice_number
     * @return [object]
    */
    public function getInvoice($invoice_number)
    {
        $url = "invoices/" . $invoice_number;

        return $this->request('get', $url);
    }

    /**
     * [getPayments Get all payments]
     * @return [array]
    */
    public function getPayments()
    {
        $url = "payments";

        return $this->request('get', $url);
    }

    /**
     * [getPayment Get a payment]
     * @param  [string] $invoice_number
     * @return [object]
    */
    public function getPayment($invoice_number)
    {
        $url = "payments/" . $invoice_number;

        return $this->request('get', $url);
    }

    /**
     * [getProfile Get profile]
     * @return [object]
    */
    public function getProfile()
    {
        $url = "me";

        return $this->request('get', $url);
    }
}
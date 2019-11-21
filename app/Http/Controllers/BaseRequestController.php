<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseRequestController extends Controller
{
    protected $client;

    public function __construct(\GuzzleHttp\Client $client = null)
    {
        $this->client = !$client ? (new \GuzzleHttp\Client()) : new $client;
    }

    protected function getHeader($response)
    {
        $headers = array();
        foreach ($response->getHeaders() as $header => $headerObject) {
            $allHeaderValues = $headerObject;
            $headers[$header] = $allHeaderValues[0];
        }
        return $headers;
    }

    public function get($url, $headers = ['Authorization' => 'Bearer '])
    {
        $request = $this->client->get($url, ['headers' => $headers]);
        $response = $request->getBody()->getContents();
        return $response;
    }

    public function post($url, $data, $headers = ['Authorization' => 'Bearer '])
    {
        $response = $this->client->request('POST', $url, ['json' => $data],['headers' => $headers]);
        return $response->getBody()->getContents();
    }

    public function put($url, $data, $headers = ['Authorization' => 'Bearer '])
    {
        $response = $this->client->request('PUT', $url, ['form_params' => $data], ['headers' => $headers]);
        return $response;
    }

    public function delete($url)
    {
        $response = $this->client->delete($url);
        return $response;
    }

    public function response($response)
    {
        return $response;
    }
}

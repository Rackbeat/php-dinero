<?php

namespace LasseRafn\Dinero\Utils;

use GuzzleHttp\Client;

class Request
{
    /** @var Client  */
    public $curl;
    /** @var string|null  */
    protected $baseUri;
    /** @var string  */
    protected $authUri = 'https://authz.dinero.dk/dineroapi/oauth/token';

    public function __construct($clientId = '', $clientSecret = '', $token = null, $org = null, $clientConfig = [], $base_uri = null)
    {
        $this->baseUri = ($base_uri ?? 'https://api.dinero.dk/v1') . ($org !== null ? "/{$org}/" : '');

        $encodedClientIdAndSecret = base64_encode("{$clientId}:{$clientSecret}");

        $headers = [];

        if ($token !== null) {
            $headers['Authorization'] = "Bearer {$token}";
            $headers['Content-Type'] = 'application/json';
        } else {
            $headers['Authorization'] = "Basic {$encodedClientIdAndSecret}";
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $this->curl = new Client(array_merge_recursive([
            'base_uri' => $this->baseUri,
            'headers'  => $headers,
        ], $clientConfig));
    }

    /**
     * Return a string with the oAuth url.
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authUri;
    }

    /**
     * @param $url string
     */
    public function setAuthUrl($url) {

        $this->authUri = $url;
    }

    /**
     * @param $url string
     */
    public function setBaseUrl($url) {

        $this->baseUri = $url;
    }

    /**
     * @return string|null
     */
    public function getBaseUrl() {

        return $this->baseUri;
    }
}

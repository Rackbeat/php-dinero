<?php

namespace LasseRafn\Dinero\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Dinero\Exceptions\DineroRequestException;
use LasseRafn\Dinero\Exceptions\DineroServerException;

class Request
{
    /**
     * @var \GuzzleHttp\Client
     */
    public $client;
    protected $baseUri;
    protected $options;
    protected $organizationID;

    /**
     * Request constructor.
     *
     * @param string $baseUri
     * @param array  $options
     * @param array  $headers
     */
    public function __construct($baseUri, $options = [], $headers = [], $org = null)
    {
        $this->options = $options;
        $this->organizationID = $org;
        $this->baseUri = $baseUri . $this->organizationID.'/';
        $options = array_merge([
            'base_uri' => $baseUri,
            'headers' => $headers,
        ], $this->options);
        $this->client = new Client($options);
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

    public function fetchEndPoint($method, $url = null, $options =[])
    {
        $options =  array_merge($this->options, $options);

        return $this->handleWithExceptions(function () use ($method, $url, $options) {
            $response = $this->client->{$method}($this->baseUri.($url ?? ''), $options);

            return json_decode((string) $response->getBody());
        });
    }

    /**
     * @param $callback
     *
     * @return mixed
     * @throws DineroRequestException
     * @throws DineroServerException
     */
    public function handleWithExceptions($callback)
    {
        try {
            return $callback();
        } catch (ClientException $exception) {
            throw new DineroRequestException($exception);
        } catch (ServerException $exception) {
            throw new DineroServerException($exception);
        }
    }
}

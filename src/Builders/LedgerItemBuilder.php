<?php

namespace LasseRafn\Dinero\Builders;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Dinero\Exceptions\DineroRequestException;
use LasseRafn\Dinero\Exceptions\DineroServerException;
use LasseRafn\Dinero\Exceptions\MethodNotImplemented;
use LasseRafn\Dinero\Models\LedgerItem;

class LedgerItemBuilder extends Builder
{
    protected $entity = 'ledgeritems';
    protected $model = LedgerItem::class;

    /**
     * @param string $parameters
     * @return \LasseRafn\Dinero\Responses\ResponseInterface|void
     * @throws MethodNotImplemented
     */
    public function get($parameters = '')
    {
        throw new MethodNotImplemented();
    }

    /**
     * @param $id
     * @return \LasseRafn\Dinero\Utils\Model|mixed|void
     * @throws MethodNotImplemented
     */
    public function find($id)
    {
        throw new MethodNotImplemented();
    }

    public function create($data = [], $fakeAttributes = true)
    {
        try {
            /**
             * We must add custom URL because v1 does not work and is deprecated for this resource creation
             * https://api.dinero.dk/openapi/index.html#tag/LedgerItems/paths/~1v1~1{organizationId}~1ledgeritems/post
             */
            $url = str_replace('v1', 'v1.1', $this->request->getBaseUrl());
            $response = $this->request->fetchEndPoint("post", "$url{$this->getEntity()}", [
                'json' => $data,
            ]);

            $responseData = (array)json_decode($response->getBody()->getContents());

            if (!$fakeAttributes) {
                $freshData = (array)$this->find($responseData[(new $this->model($this->request))->getPrimaryKey()]);
            }

            $mergedData = array_merge($responseData, $fakeAttributes ? $data : $freshData);

            return new $this->model($this->request, $mergedData);
        } catch (ClientException $exception) {
            throw new DineroRequestException($exception);
        } catch (ServerException $exception) {
            throw new DineroServerException($exception);
        }
    }
}

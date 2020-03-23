<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Exceptions\DineroRequestException;
use LasseRafn\Dinero\Exceptions\DineroServerException;
use LasseRafn\Dinero\Exceptions\MethodNotImplemented;
use LasseRafn\Dinero\Models\File;

class FileBuilder extends Builder
{
    protected $entity = 'files';
    protected $model = File::class;

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

    /**
     * For this resource we can only upload file, you  can't add any parameters or anything and there is no GET for this resource
     *
     * @param array $data
     * @param bool $fakeAttributes
     * @return \LasseRafn\Dinero\Utils\Model|mixed
     * @throws DineroRequestException
     * @throws DineroServerException
     * @throws MethodNotImplemented
     */
    public function create( $data = [], $fakeAttributes = true ) {
        try {
            $response = $this->request->curl->post( (string)($this->getEntity()), [
                'multipart' => $data,
            ] );

            $responseData = (array) json_decode( $response->getBody()->getContents() );

            return new $this->model( $this->request, $responseData );
        } catch ( ClientException $exception ) {
            throw new DineroRequestException( $exception );
        } catch ( ServerException $exception ) {
            throw new DineroServerException( $exception );
        }
    }
}

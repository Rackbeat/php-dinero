<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Exceptions\DineroRequestException;
use LasseRafn\Dinero\Exceptions\DineroServerException;
use LasseRafn\Dinero\Models\DepositAccount;
use Illuminate\Support\Facades\Log;

class DepositAccountBuilder extends Builder
{
    protected $entity = 'accounts/deposit';
    protected $model = DepositAccount::class;

    /**
     * @inheritDoc
     */
    public function get($parameters = '')
    {
        try {
            $dineroApiResponse = $this->request->fetchEndPoint('get', "{$this->entity}{$parameters}");
        } catch (ClientException $exception) {
            Log::debug('ERROR: '. $exception->getMessage());

            throw new DineroRequestException($exception);
        } catch (ServerException $exception) {
            Log::debug('ERROR: '. $exception->getMessage());

            throw new DineroServerException($exception);
        }

        $request = $this->request;

        $items = array_map(function ($item) use ($request) {
            return new $this->model($item);
        }, $dineroApiResponse);

        return (object)['items' => $items];
    }
}

<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Models\ManualVoucher;

class ManualVoucherBuilder extends Builder
{
    protected $entity = 'vouchers/manuel';
    protected $model = ManualVoucher::class;

    /**
     * @param $orgId
     * @param $id
     * @param $timestamp
     *
     * @return mixed
     */
    public function book($orgId, $id, $timestamp)
    {
        return $this->request->fetchEndPoint('post','vouchers/manuel/' . $id . '/book', [
            'json' => [
                'Timestamp' => $timestamp,
            ]
        ]);
    }
}

<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Models\PurchaseVoucher;

class PurchaseVoucherBuilder extends Builder
{
    protected $entity = 'vouchers/purchase';
    protected $model = PurchaseVoucher::class;

    /**
     * @param $orgId
     * @param $id
     * @param $timestamp
     *
     * @return mixed
     */
    public function book($orgId, $id, $timestamp) {

        return $this->request->curl->post('https://api.dinero.dk/v1/' . $orgId . '/vouchers/purchase/' . $id . '/book', [
            'json' => [
                'Timestamp' => $timestamp,
            ]
        ]);
    }
}

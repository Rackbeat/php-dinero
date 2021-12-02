<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Models\Invoice;
use LasseRafn\Dinero\Requests\PaymentRequestBuilder;

class InvoiceBuilder extends Builder
{
    protected $entity = 'invoices';
    protected $model = Invoice::class;

    /**
     * @param $id
     *
     * @return PaymentRequestBuilder
     */
    public function payments($id) {
        return new PaymentRequestBuilder( new PaymentBuilder( $this->request, "invoices/{$id}/payments" ) );
    }

    /**
     * @param $orgId
     * @param $id
     * @param $timestamp
     *
     * @return mixed
     */
    public function book($orgId, $id, $timestamp)
    {
        return $this->request->curl->post('https://api.dinero.dk/v1/' . $orgId . '/invoices/' . $id . '/book', [
            'json' => [
                'Timestamp' => $timestamp,
            ]
        ]);
    }

    /**
     * Send invoice to customer by email @todo Add possible email parameters from https://api.dinero.dk/openapi/index.html#tag/Invoices/paths/~1v1~1{organizationId}~1invoices~1{guid}~1email/post
     *
     * @param $orgId
     * @param $id
     *
     * @return mixed
     */
    public function send($orgId, $id)
    {
        return $this->request->curl->post('https://api.dinero.dk/v1/' . $orgId . '/invoices/' . $id . '/email');
    }
}

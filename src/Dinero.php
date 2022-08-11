<?php

namespace LasseRafn\Dinero;

use LasseRafn\Dinero\Builders\ContactBuilder;
use LasseRafn\Dinero\Builders\CreditnoteBuilder;
use LasseRafn\Dinero\Builders\DepositAccountBuilder;
use LasseRafn\Dinero\Builders\EntryAccountBuilder;
use LasseRafn\Dinero\Builders\FileBuilder;
use LasseRafn\Dinero\Builders\InvoiceBuilder;
use LasseRafn\Dinero\Builders\LedgerItemBuilder;
use LasseRafn\Dinero\Builders\ManualVoucherBuilder;
use LasseRafn\Dinero\Builders\PaymentBuilder;
use LasseRafn\Dinero\Builders\ProductBuilder;
use LasseRafn\Dinero\Builders\PurchaseVoucherBuilder;
use LasseRafn\Dinero\Exceptions\DineroRequestException;
use LasseRafn\Dinero\Exceptions\DineroServerException;
use LasseRafn\Dinero\Requests\ContactRequestBuilder;
use LasseRafn\Dinero\Requests\CreditnoteRequestBuilder;
use LasseRafn\Dinero\Requests\DepositAccountRequestBuilder;
use LasseRafn\Dinero\Requests\EntryAccountRequestBuilder;
use LasseRafn\Dinero\Requests\InvoiceRequestBuilder;
use LasseRafn\Dinero\Requests\ManualVoucherRequestBuilder;
use LasseRafn\Dinero\Requests\PaymentRequestBuilder;
use LasseRafn\Dinero\Requests\ProductRequestBuilder;
use LasseRafn\Dinero\Requests\PurchaseVoucherRequestBuilder;
use LasseRafn\Dinero\Utils\Request;

class Dinero
{
    /**
     * @var Request
     */
    protected $request;
    protected $baseUri;
    protected $options;
    protected $headers;
    protected $organizationID;

    /**
     * Rackbeat constructor.
     *
     * @param null  $baseUri Base URI
     * @param array $options Custom Guzzle options
     * @param array $headers Custom Guzzle headers
     */
    public function __construct($baseUri, $options = [], $headers = [], $org = null)
    {
        $this->baseUri = $baseUri ?? config('dinero.api_url');
        $this->options = $options;
        $this->headers = $headers;
        $this->organizationID = $org;
        $this->initRequest($this->baseUri, $this->options, $this->headers, $this->organizationID);
    }

    public function getOrgId()
    {
        return $this->organizationID;
    }

    public function getBaseUrl()
    {
        return $this->request->getBaseUrl();
    }

    public function setBaseUrl($url) {

        $this->request->setBaseUrl($url);
    }

    public function contacts()
    {
        return new ContactRequestBuilder(new ContactBuilder($this->request));
    }

    public function invoices()
    {
        return new InvoiceRequestBuilder(new InvoiceBuilder($this->request));
    }

    public function paymentsForInvoice($invoiceId)
    {
        return new PaymentRequestBuilder(new PaymentBuilder($this->request, "invoices/{$invoiceId}/payments"));
    }

    public function products()
    {
        return new ProductRequestBuilder(new ProductBuilder($this->request));
    }

    public function creditnotes()
    {
        return new CreditnoteRequestBuilder(new CreditnoteBuilder($this->request));
    }

    public function entryAccounts()
    {
        return new EntryAccountRequestBuilder(new EntryAccountBuilder($this->request));
    }

    public function depositAccounts() {

        return new DepositAccountRequestBuilder(new DepositAccountBuilder($this->request));
    }

    public function purchaseVouchers()
    {

        return new PurchaseVoucherRequestBuilder(new PurchaseVoucherBuilder($this->request));
    }

    /**
     * @return FileBuilder
     */
    public function files(): FileBuilder
    {
        return new FileBuilder($this->request);
    }

    /**
     * @return LedgerItemBuilder
     */
    public function ledgerItems(): LedgerItemBuilder
    {
        return new LedgerItemBuilder($this->request);
    }

    /**
     * @return ManualVoucherRequestBuilder
     */
    public function manualVouchers(): ManualVoucherRequestBuilder
    {
        return new ManualVoucherRequestBuilder(new ManualVoucherBuilder($this->request));
    }

    /**
     * @param       $baseUri
     * @param  array  $options
     * @param  array  $headers
     * @param  null  $organizationID
     */
    private function initRequest($baseUri, $options = [], $headers = [], $organizationID = null)
    {
        $this->request = new Request($baseUri, $options, $headers, $organizationID);
    }

    /**
     * @param $method
     * @param $url
     *
     * @return mixed
     * @throws DineroRequestException
     * @throws DineroServerException
     */
    public function fetchEndPoint($method, $url = null)
    {
        return $this->request->handleWithExceptions(function () use ($method, $url) {
            $response = $this->request->client->{$method}($this->baseUri.($url ?? ''), $this->options);
            return json_decode((string) $response->getBody());
        });
    }

    public function getClient()
    {
        return $this->request->client;
    }

}

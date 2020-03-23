<?php

namespace LasseRafn\Dinero\Requests;

use LasseRafn\Dinero\Builders\Builder;
use LasseRafn\Dinero\Utils\RequestBuilder;

class ManualVoucherRequestBuilder extends RequestBuilder
{
    public function __construct(Builder $builder)
    {
        $this->parameters['fields'] = 'Number,Guid,ContactGuid,VoucherDate,PaymentDate,Status,Timestamp,VoucherNumber,FileGuid,$DepositAccountNumber,ExternalReference';

        parent::__construct($builder);
    }
}

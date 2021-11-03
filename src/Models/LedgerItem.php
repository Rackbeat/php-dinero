<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Utils\Model;

class LedgerItem extends Model
{
    protected $entity = 'ledgeritems';
    public $primaryKey = 'Id';

    public $id;
    public $VoucherNumber;
    public $AccountNumber;
    public $AccountVatCode;
    public $Amount;
    public $BalancingAccountNumber;
    public $BalancingAccountVatCode;
    public $Description;
    public $VoucherDate;
    public $FileGuid;
}

<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Utils\Model;

class PurchaseVoucher extends Model
{
    protected $entity = 'vouchers/purchase';
    public $primaryKey = 'Guid';

    public $PaymentDate;
    public $VoucherDate;
    public $Status;
    public $ContactGuid;
    public $Guid;
    public $Timestamp;
    public $VoucherNumber;
    public $FileGuid;
    public $RegionKey;
    public $DepositAccountNumber;
    public $ExternalReference;

    /** @var array */
    public $Lines;
}

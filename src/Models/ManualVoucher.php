<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Utils\Model;

class ManualVoucher extends Model
{
    // Manuel is not typo, that's how it is in docs
    protected $entity = 'vouchers/manuel';
    public $primaryKey = 'Guid';

    public $VoucherDate;
    public $Status;
    public $Guid;
    public $Timestamp;
    public $VoucherNumber;
    public $FileGuid;
    public $DepositAccountNumber;
    public $ExternalReference;

    /** @var array */
    public $Lines;
}

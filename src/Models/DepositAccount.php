<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Utils\Model;

class DepositAccount extends Model
{
    protected $entity = 'accounts/deposit';
    public $primaryKey = 'AccountNumber';

    public $AccountNumber;
    public $Name;
}

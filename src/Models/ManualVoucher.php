<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Utils\Model;

class ManualVoucher extends Model
{
    // Manuel is not typo, that's how it is in docs
    protected $entity = 'vouchers/manuel';
    protected $primaryKey = 'Guid';

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



    /**
     * @param $orgId
     * @return mixed
     */
    public function book($orgId) {

        return $this->request->curl->post('https://api.dinero.dk/v1/' . $orgId . '/' . $this->entity . '/' . $this->{$this->primaryKey} . '/book', [

            'json' => [

                'Timestamp' => $this->{'Timestamp'},
            ]
        ]);
    }
}

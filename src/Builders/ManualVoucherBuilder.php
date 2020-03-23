<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Models\ManualVoucher;

class ManualVoucherBuilder extends Builder
{
    protected $entity = 'vouchers/manuel';
    protected $model = ManualVoucher::class;
}

<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Builders\PaymentBuilder;
use LasseRafn\Dinero\Requests\PaymentRequestBuilder;
use LasseRafn\Dinero\Utils\Model;

class File extends Model
{
	protected $entity     = 'files';
	public $primaryKey = 'Guid';

	public $Guid;
}

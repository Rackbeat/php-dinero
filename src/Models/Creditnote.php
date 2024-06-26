<?php

namespace LasseRafn\Dinero\Models;

use LasseRafn\Dinero\Utils\Model;

class Creditnote extends Model
{
	protected $entity     = 'sales/creditnotes';
	public $primaryKey = 'Guid';

	public $CreditNoteFor;
	public $Status;
	public $ContactGuid;
	public $Guid;
	public $TimeStamp;
	public $CreatedAt;
	public $UpdatedAt;
	public $DeletedAt;
	public $Number;
	public $ContactName;
	public $TotalExclVat;
	public $TotalVatableAmount;
	public $TotalInclVat;
	public $TotalNonVatableAmount;
	public $TotalVat;
	public $PaymentDate;
	public $Type;
	public $TotalInclVatInDkk;
	public $TotalExclVatInDkk;
	public $MailOutStatus;

	/** @var array */
	public $TotalLines;
	public $Currency;
	public $Language;
	public $ExternalReference;
	public $Description;
	public $Comment;
	public $Date;

	/** @var array */
	public $ProductLines;
	public $Address;
}

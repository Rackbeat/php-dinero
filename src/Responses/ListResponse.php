<?php

namespace LasseRafn\Dinero\Responses;


class ListResponse implements ResponseInterface
{
	/** @var array */
	public $items;

	public $page;
	public $pageSize;
	public $maxPageSizeAllowed;

	public $result;
	public $resultWithoutFilter;

	public function __construct( $jsonResponse, $collectionKey = 'Collection' )
	{
		$this->items = $jsonResponse->{$collectionKey};
	}

	public function setItems( array $items ) {
		$this->items = $items;

		return $this;
	}
}

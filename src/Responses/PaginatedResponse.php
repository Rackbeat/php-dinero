<?php

namespace LasseRafn\Dinero\Responses;


class PaginatedResponse implements ResponseInterface
{
    /** @var array */
    public $items;

    public $page;
    public $pageSize;
    public $maxPageSizeAllowed;

    public $result;
    public $resultWithoutFilter;

    public function __construct( $jsonResponse, $collectionKey = 'Collection')
    {
        $this->items = $jsonResponse->{$collectionKey};

        $this->page = $jsonResponse->Pagination->Page;
        $this->pageSize = $jsonResponse->Pagination->PageSize;
        $this->maxPageSizeAllowed = $jsonResponse->Pagination->MaxPageSizeAllowed;
        $this->result = $jsonResponse->Pagination->Result;
        $this->resultWithoutFilter = $jsonResponse->Pagination->ResultWithoutFilter;
    }

    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}

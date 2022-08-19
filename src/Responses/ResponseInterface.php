<?php

namespace LasseRafn\Dinero\Responses;


Interface ResponseInterface
{
	public function __construct( $response, $collectionKey = 'Collection' );

	public function setItems( array $items );
}

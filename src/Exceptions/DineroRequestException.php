<?php

namespace LasseRafn\Dinero\Exceptions;

use GuzzleHttp\Exception\ClientException;

class DineroRequestException extends \Exception
{
	public $validationErrors = [];

	public function __construct( ClientException $clientException )
	{
		$message = $clientException->getMessage();
		$code = $clientException->getCode();

		if ( $clientException->hasResponse() && $clientException->getResponse() !== null ) {
			$code = $clientException->getResponse()->getStatusCode();
			$responseBody = $clientException->getResponse()->getBody()->getContents();

			$messageResponse = json_decode( $responseBody );

			if ( ! $messageResponse ) {
				$message = $responseBody;
			} else {
				if ( isset( $messageResponse->message ) ) {
					$message = "{$messageResponse->message}: ";
				} elseif ( isset( $messageResponse->error ) ) {
					$message = "{$messageResponse->error}: ";
				}

				if ( isset( $messageResponse->validationErrors ) ) {
					foreach ( $messageResponse->validationErrors as $key => $validationError ) {
						$this->validationErrors[ $key ][] = $validationError;
						$message                          .= "{$key}: {$validationError}\n";
					}
				}

				if ( isset( $messageResponse->languageSpecificMessages ) ) {
					foreach ( $messageResponse->languageSpecificMessages as $error ) {
						$message .= "{$error->property}: {$error->message}\n";
					}
				}

				if ( $message === '' ) {
					$message = json_encode( $messageResponse );
				}
			}
		}

		parent::__construct( $message, $code, $clientException->getPrevious() );
	}
}
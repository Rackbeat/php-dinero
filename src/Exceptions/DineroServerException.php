<?php

namespace LasseRafn\Dinero\Exceptions;

use GuzzleHttp\Exception\ServerException;

class DineroServerException extends \Exception
{
    public function __construct(ServerException $serverException)
    {
        $message = $serverException->getMessage();
	    $code = $serverException->getCode();

	    if ( $serverException->hasResponse() && $serverException->getResponse() !== null ) {
		    $code = $serverException->getResponse()->getStatusCode();
		    $responseBody = $serverException->getResponse()->getBody()->getContents();

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

        parent::__construct($message, $code, $serverException->getPrevious());
    }
}

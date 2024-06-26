<?php

namespace LasseRafn\Dinero\Utils;

use Generator;
use LasseRafn\Dinero\Builders\Builder;
use LasseRafn\Dinero\Responses\PaginatedResponse;

class RequestBuilder
{
	private $builder;

	protected $parameters = [];
	protected $dateFormat = 'Y-m-d';
    protected $dateTimeFormat = 'Y-m-d\TH:m:s\Z';

	public function __construct( Builder $builder ) {
		$this->parameters['page']     = 0;
		$this->parameters['pageSize'] = 100;

		$this->builder = $builder;
	}

	/**
	 * Select only some fields.
	 *
	 * @param array|int|string $fields
	 *
	 * @return $this
	 */
	public function select( $fields ) {

        if (is_array($fields)) {
            $this->parameters['fields'] = implode(',', $fields);
        } elseif (is_string($fields) || is_int($fields)) {
            $this->parameters['fields'] = $fields;
        }

		return $this;
	}

	/**
	 * Used for pagination, to set current page.
	 * Starts at zero.
	 *
	 * @param $page
	 *
	 * @return $this
	 */
	public function page( $page ) {
		$this->parameters['page'] = $page;

		return $this;
	}

	/**
	 * Used for pagination, to set pagesize.
	 * Default: 100, max: 1000.
	 *
	 * @param $pageSize
	 *
	 * @return $this
	 */
	public function perPage( $pageSize ) {
		if ( $pageSize > 1000 ) {
			$pageSize = 1000;
		}

		$this->parameters['pageSize'] = $pageSize;

		return $this;
	}

	/**
	 * Add a filter to only show models that are deleted.
	 *
	 * @return $this
	 */
	public function deletedOnly() {
		$this->parameters['deletedOnly'] = 'true';

		return $this;
	}

	/**
	 * Remove the filter that only show models that are deleted.
	 *
	 * @return $this
	 */
	public function notDeletedOnly() {
		unset( $this->parameters['deletedOnly'] );

		return $this;
	}

	/**
	 * Add a filter to only show models changed since %.
	 *
	 * @param \DateTime $date
	 *
	 * @return $this
	 */
	public function since( \DateTime $date ) {
        $this->parameters['changesSince'] = $date->format($this->dateTimeFormat);

		return $this;
	}

	/**
	 * Build URL parameters.
	 *
	 * @return string
	 */
	private function buildParameters()
    {
		$parameters = http_build_query( $this->parameters );

		if ( $parameters !== '' ) {
			$parameters = "?{$parameters}";
		}

		return $parameters;
	}

	/**
	 * Send a request to the API to get models.
	 *
	 * @return PaginatedResponse
	 */
	public function get() {
		$response = $this->builder->get($this->buildParameters());

		return $response;
	}

	/**
	 * Send a request to the API to get models,
	 * manually paginated to get all objects.
	 *
	 * We specify a minor usleep to prevent some
	 * weird bugs. You can disable this if you
	 * desire, however I ran into trouble with
	 * larger datasets.
	 *
	 * @param bool $sleep
	 *
	 * @return array
	 */
	public function all( $sleep = true ) {
		$items = [];
		$this->page( 0 );

		$response = $this->builder->get( $this->buildParameters() );

		if ( $response instanceof PaginatedResponse ) {
			while ( count( $response->items ) > 0 ) {
				foreach ( $response->items as $item ) {
					$items[] = $item;
				}

				$this->page( $this->getPage() + 1 );

				if ( $sleep ) {
					usleep( 200 );
				}

				$response = $this->builder->get( $this->buildParameters() );
			}
		} else {
			foreach ( $response->items as $item ) {
				$items[] = $item;
			}
		}

		return $items;
	}

    /**
     * It will iterate over all pages until it does not receive empty response, you can also set query parameters,
     * Return a Generator that you' handle first before querying the next offset
     *
     * @param int $chunkSize
     *
     * @return Generator
     */
    public function allWithGenerator(int $chunkSize = 20)
    {
        $this->page(0);

        $response = function () use ($chunkSize) {
            $this->perPage($chunkSize);
            // will also cast to the right model items
            return $this->builder->get($this->buildParameters());
        };

        do {
            $resp = $response();

            $countResults = count($resp->items);
            if ($countResults === 0) {
                break;
            }
            // make a generator of the results and return them
            // so the logic will handle them before the next iteration
            // in order to avoid memory leaks
            foreach ($resp->items as $result) {
                yield $result;
            }

            unset($resp);

            // will delay the next page request for 1 second to make sure it won't throttle on dinero side
            // sleep makes sense here as this will loop for one connection and total number of connections
            // is limited by total number of long-redis-connection processes
            sleep(1);

            $this->page( $this->getPage() + 1 );
        } while ($countResults === $chunkSize);
    }

	/**
	 * Send a request to the API to get a single model.
	 *
	 * @param $guid
	 *
	 * @return Model|mixed
	 */
	public function find( $guid ) {
		return $this->builder->find( $guid );
	}

	/**
	 * Creates a model from a data array.
	 * Sends a API request.
	 *
	 * $fakeAttributes decides if we should create the model,
	 * from the data sent, or if we should refetch after creation.
	 * Because the Dinero API does not return the model after creation,
	 * so we can either send a GET request, or fake the data.
	 * Obviously we wont return a faked model if we get an error, so
	 * faking should be more than enough for most cases; unique IDs are
	 * returned from the creation response.
	 *
	 * When fakeAttribute is false, we send a GET request to the API,
	 * to get the model.
	 *
	 * @param array $data
	 * @param bool  $fakeAttributes
	 *
	 * @return Model
	 */
	public function create( $data = [], $fakeAttributes = true ) {
		return $this->builder->create( $data, $fakeAttributes );
	}

    public function update($id, $data) {
        return $this->builder->update($id, $data);
    }

    public function delete($id) {
        $this->builder->delete($id);
    }

	/**
	 * Returns the set page.
	 *
	 * @return int
	 */
	public function getPage() {
		return $this->parameters['page'];
	}

	/**
	 * Returns the perPage.
	 *
	 * @return int
	 */
	public function getPerPage() {
		return $this->parameters['pageSize'];
	}

	/**
	 * Returns the fields.
	 *
	 * @return array|null
	 */
	public function getSelectedFields() {
		return $this->parameters['fields'] ?? null;
	}

	/**
	 * Returns deletedOnly state.
	 *
	 * @return string
	 */
	public function getDeletedOnlyState() {
		return $this->parameters['deletedOnly'] ?? 'false';
	}

	/**
	 * Returns changes since.
	 *
	 * @return string|null
	 */
	public function getSince() {
		return $this->parameters['changesSince'] ?? null;
	}

	/**
	 * Returns all parameters as an array.
	 *
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

    public function getBuilder()
    {
        return $this->builder;
    }
}

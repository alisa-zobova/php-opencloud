<?php

namespace OpenCloud\LoadBalancer\Collection;

use GuzzleHttp\Psr7\Query;
use OpenCloud\Common\Collection\PaginatedIterator;

class LoadBalancerIterator extends PaginatedIterator
{
    private $nextElement;

    public function constructNextUrl()
    {
        $url = parent::constructNextUrl();

        // We need to return n+1 items in order to grab the relevant marker value
        $query = Query::parse($url->getQuery());
        $query['limit'] = $query['limit'] + 1;
        return $url->setQuery($query);
    }

    public function updateMarkerToCurrent()
    {
        $this->setMarkerFromElement($this->nextElement);
    }

    public function parseResponseBody($body)
    {
        $response = parent::parseResponseBody($body);

        if (count($response) >= $this->getOption('limit.page')) {
            // Pop last element and save (we will need it for the next marker)
            $this->nextElement = array_pop($response);
        }

        return $response;
    }
}

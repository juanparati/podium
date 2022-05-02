<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Podium;

abstract class RequestBase implements RequestContract
{
    /**
     * HTTP methods used in the requests.
     */
    const METHOD_POST = 'POST';
    const METHOD_GET  = 'GET';
    const METHOD_PUT  = 'PUT';
    const METHOD_DELETE = 'DELETE';


    /**
     * Constructor.
     *
     * @param Podium $podium
     */
    public function __construct(protected Podium $podium) {}

}
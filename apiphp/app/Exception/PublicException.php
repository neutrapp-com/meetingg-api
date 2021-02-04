<?php

namespace Meetingg\Exception;

use Exception;

/**
 * Public Exception Class
 * throw a public error message
 * @extends Exception
 */
class PublicException extends Exception
{
    protected array $headers = [];

    public function __construct($message = null, $code = 0, array $headers = [], Exception $previous = null)
    {
        $this->setHeaders($headers);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get Exception HTTP Headers
     *
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }


    /**
     * Set Exception HTTP Headers
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers) : void
    {
        $this->headers = $headers;
    }
}

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
    protected array $data = [];
    protected array $headers = [];

    public function __construct($message = null, $code = 0, array $headers = [], array $data = [], Exception $previous = null)
    {
        $this->setHeaders($headers);
        $this->setData($data);
        if ($message != null) {
            parent::__construct($message, $code, $previous);
        }
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

    /**
     * Get the value of data
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    public function setData(array $data) : void
    {
        $this->data = $data;
    }
}

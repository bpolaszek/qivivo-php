<?php

namespace BenTools\Qivivo\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MessageInterface
 *
 * A message is composed of a PSR-7 request and a parser for the associated response.
 * The parser is optionnal (then parse() should return null)
 */
interface MessageInterface {

    /**
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * @param ResponseInterface $response
     * @return mixed|null
     */
    public function parse(ResponseInterface $response);
}
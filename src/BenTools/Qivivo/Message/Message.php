<?php

namespace BenTools\Qivivo\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Message implements MessageInterface {

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var callable
     */
    private $responseParser;

    /**
     * Message constructor.
     */
    public function __construct(RequestInterface $request, callable $responseParser = null) {
        $this->request        = $request;
        $this->responseParser = $responseParser;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     * @return $this - Provides Fluent Interface
     */
    public function setRequest(RequestInterface $request) {
        $this->request = $request;
        return $this;
    }

    /**
     * @param callable $responseParser
     * @return $this - Provides Fluent Interface
     */
    public function setResponseParser($responseParser) {
        $this->responseParser = $responseParser;
        return $this;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function parse(ResponseInterface $response) {
        $responseParser = $this->responseParser;
        return is_callable($responseParser) ? $responseParser($response) : null;
    }
}
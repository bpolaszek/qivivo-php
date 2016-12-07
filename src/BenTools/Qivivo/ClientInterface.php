<?php

namespace BenTools\Qivivo;

use BenTools\Qivivo\Message\MessageInterface;

interface ClientInterface {

    const BASE_URI = 'https://data.qivivo.com/api';

    /**
     * Executes a message and renders the response.
     * @param MessageInterface $message
     * @return mixed
     */
    public function execute(MessageInterface $message);

    /**
     * Returns the OAuth2 Access token for futher reuse.
     * If null, authenticate and get one.
     * @return string
     */
    public function getAccessToken();
}
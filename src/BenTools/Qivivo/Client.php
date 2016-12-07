<?php

namespace BenTools\Qivivo;
use BenTools\Qivivo\Endpoint\ArrivalEndpoint;
use BenTools\Qivivo\Endpoint\DeviceEndpoint;
use BenTools\Qivivo\Endpoint\ProgramEndpoint;
use BenTools\Qivivo\Endpoint\ThermostatEndpoint;
use BenTools\Qivivo\Message\MessageInterface;
use GuzzleHttp\Client AS Guzzle;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class Client implements ClientInterface {

    const CURRENT_API_VERSION = 2;

    /**
     * @var Guzzle
     */
    private $guzzle;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var int
     */
    private $version;

    /**
     * Client constructor.
     * @param Guzzle $guzzle
     * @param null   $clientId
     * @param null   $clientSecret
     * @param null   $accessToken
     * @param int    $version
     */
    public function __construct(Guzzle $guzzle,
                                $clientId = null,
                                $clientSecret = null,
                                $accessToken = null,
                                $version = self::CURRENT_API_VERSION) {
        $this->guzzle       = $guzzle;
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken  = $accessToken;
        $this->version      = $version;
    }

    /**
     * @return Endpoint\DeviceEndpoint
     */
    public function device() {
        return new DeviceEndpoint();
    }

    /**
     * @return Endpoint\ThermostatEndpoint
     */
    public function thermostat() {
        return new ThermostatEndpoint();
    }

    /**
     * @return ArrivalEndpoint
     */
    public function arrival() {
        return new ArrivalEndpoint();
    }

    /**
     * @return ProgramEndpoint
     */
    public function program() {
        return new ProgramEndpoint();
    }

    /**
     * Executes a message and renders the response.
     * @param MessageInterface $message
     * @return mixed
     */
    public function execute(MessageInterface $message) {
        $request  = $message->getRequest();
        $request  = $this->prependBaseUri($request);
        $request  = $this->authenticateRequest($request);
        $response = $this->guzzle->send($request);
        return $message->parse($response);
    }

    /**
     * Prepends base URI to the request URI
     * @param RequestInterface $request
     * @return RequestInterface
     */
    private function prependBaseUri(RequestInterface $request) {
        $baseUri    = new Uri($this->getBaseUri());
        $requestUri = $request->getUri();
        $finalUri   = $baseUri->withPath($baseUri->getPath() . $requestUri->getPath());
        $finalUri   = $finalUri->withQuery($requestUri->getQuery());
        return $request->withUri($finalUri);
    }

    /**
     * @return string
     */
    private function authenticate() {
        $provider          = new OAuth2\Provider\Qivivo([
            'clientId'     => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);
        $this->accessToken = $provider->getAccessToken('client_credentials')->getToken();
        return $this->accessToken;
    }

    /**
     * Authenticates the PSR-7 request.
     * @param RequestInterface $request
     * @return RequestInterface
     */
    private function authenticateRequest(RequestInterface $request) {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->getAccessToken()));
    }

    /**
     * @return string
     */
    public function getClientId() {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return $this - Provides Fluent Interface
     */
    public function setClientId($clientId) {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret() {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     * @return $this - Provides Fluent Interface
     */
    public function setClientSecret($clientSecret) {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken() {
        return null === $this->accessToken ? $this->authenticate() : $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return $this - Provides Fluent Interface
     */
    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return int
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @param int $version
     * @return $this - Provides Fluent Interface
     */
    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUri() {
        return sprintf('%s/v%s', static::BASE_URI, $this->version);
    }
}
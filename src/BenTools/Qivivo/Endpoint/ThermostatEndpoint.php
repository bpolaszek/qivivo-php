<?php

namespace BenTools\Qivivo\Endpoint;

use BenTools\Qivivo\Exception\UnexpectedTypeException;
use BenTools\Qivivo\Message\Message;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class ThermostatEndpoint {

    /**
     * @param $thermostatId
     * @return Message
     */
    public function getTemperature($thermostatId) {
        $request = new Request('GET', sprintf('/devices/thermostats/%s/temperature', $thermostatId));
        return new Message($request, function (ResponseInterface $response) {
            $json = \GuzzleHttp\json_decode($response->getBody(), true);
            return (float) $json['temperature'];
        });
    }

    /**
     * @param $thermostatId
     * @param $temperature - in celsius degrees
     * @param $duration - in minutes
     * @return Message
     * @throws UnexpectedTypeException
     */
    public function setTemperature($thermostatId, $temperature, $duration) {
        if (!is_numeric($temperature)) {
            throw new UnexpectedTypeException($temperature, 'float or integer');
        }
        if (!is_int($duration)) {
            throw new UnexpectedTypeException($duration, 'integer (number of minutes)');
        }
        $body = \GuzzleHttp\json_encode([
            'temperature' => $temperature,
            'duration'    => $duration,
        ]);
        $request = new Request('POST', sprintf('/devices/thermostats/%s/temperature/temporary-instruction', $thermostatId), ['Content-Type' => 'application/json'], $body);
        return new Message($request);
    }

    /**
     * @param $thermostatId
     * @return Message
     */
    public function cancelTemporaryInstruction($thermostatId) {
        $request = new Request('DELETE', sprintf('/devices/thermostats/%s/temperature/temporary-instruction', $thermostatId));
        return new Message($request);
    }
}
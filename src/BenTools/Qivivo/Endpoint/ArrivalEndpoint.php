<?php

namespace BenTools\Qivivo\Endpoint;

use BenTools\Qivivo\Exception\UnexpectedTypeException;
use BenTools\Qivivo\Message\Message;
use GuzzleHttp\Psr7\Request;

class ArrivalEndpoint {

    /**
     * (Smart mode only) sets the delay in minutes before arrival.
     * @param $thermostatId
     * @param $delay
     * @return Message
     * @throws UnexpectedTypeException
     */
    public function setTimeLeftBeforeArrival($thermostatId, $delay) {
        if (!is_numeric($delay)) {
            throw new UnexpectedTypeException($delay, 'integer (number of minutes)');
        }
        $body = \GuzzleHttp\json_encode([
            'duration' => $delay,
        ]);
        $request = new Request('POST', sprintf('/devices/thermostats/%s/arrival', $thermostatId), ['Content-Type' => 'application/json'], $body);
        return new Message($request);
    }

    /**
     * (Smart mode only) sets the estimated date of arrival.
     * @param                    $thermostatId
     * @param \DateTimeInterface $arrival
     * @return Message
     * @throws \RuntimeException
     */
    public function setETA($thermostatId, \DateTimeInterface $arrival) {
        $now = new \DateTimeImmutable();
        if ($arrival <= $now) {
            throw new \RuntimeException('$arrival must be in the future.');
        }
        if ($arrival instanceof \DateTime) {
            $arrival = \DateTimeImmutable::createFromMutable($arrival);
        }
        $delay = round(($arrival->format('U') - $now->format('U')) / 60);
        return $this->setTimeLeftBeforeArrival($thermostatId, $delay);
    }

    /**
     * (Smart mode only) cancels the arrival instruction.
     * @param $thermostatId
     * @return Message
     */
    public function cancelArrivalInstruction($thermostatId) {
        $request = new Request('DELETE', sprintf('/devices/thermostats/%s/arrival', $thermostatId));
        return new Message($request);
    }
}
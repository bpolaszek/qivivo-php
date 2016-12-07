<?php

namespace BenTools\Qivivo\Endpoint;

use BenTools\Qivivo\Message\Message;
use GuzzleHttp\Psr7\Request;

class AbsenceEndpoint {

    /**
     * Sets an absence period.
     * @param                    $thermostatId
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return Message
     */
    public function setAbsencePeriod($thermostatId, \DateTimeInterface $startDate, \DateTimeInterface $endDate) {
        $body = \GuzzleHttp\json_encode([
            'start_date' => $startDate->format('YmdHi'),
            'end_date' => $endDate->format('YmdHi'),
        ]);
        $request = new Request('POST', sprintf('/devices/thermostats/%s/absence', $thermostatId), ['Content-Type' => 'application/json'], $body);
        return new Message($request);
    }

    /**
     * Cancels the absence instruction.
     * @param $thermostatId
     * @return Message
     */
    public function cancelAbsenceInstruction($thermostatId) {
        $request = new Request('DELETE', sprintf('/devices/thermostats/%s/absence', $thermostatId));
        return new Message($request);
    }

}
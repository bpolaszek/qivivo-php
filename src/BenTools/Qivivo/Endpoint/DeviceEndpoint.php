<?php

namespace BenTools\Qivivo\Endpoint;

use BenTools\Qivivo\Message\Message;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class DeviceEndpoint {

    /**
     * Lists all devices.
     * @return Message
     */
    public function getDevices() {
        $request = new Request('GET', '/devices');
        return new Message($request, function (ResponseInterface $response) {
            $json = \GuzzleHttp\json_decode($response->getBody(), true);
            return $json;
        });
    }

    /**
     * List all thermostats UUIDs
     * @return Message
     */
    public function getThermostatIds() {
        $request = new Request('GET', '/devices');
        return new Message($request, function (ResponseInterface $response) {
            $json = \GuzzleHttp\json_decode($response->getBody(), true);
            $uuids = [];
            foreach ($json['devices'] AS $device) {
                if ($device['type'] == 'thermostat') {
                    $uuids[] = $device['uuid'];
                }
            }
            return $uuids;
        });
    }

    /**
     * Get the 1st thermostat's UUID
     * @return Message
     */
    public function getThermostatId() {
        $request = new Request('GET', '/devices');
        return new Message($request, function (ResponseInterface $response) {
            $json = \GuzzleHttp\json_decode($response->getBody(), true);
            $uuids = [];
            foreach ($json['devices'] AS $device) {
                if ($device['type'] == 'thermostat') {
                    $uuids[] = $device['uuid'];
                }
            }
            return isset($uuids[0]) ? $uuids[0] : null;
        });
    }

}
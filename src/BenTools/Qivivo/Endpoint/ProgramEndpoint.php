<?php

namespace BenTools\Qivivo\Endpoint;

use BenTools\Qivivo\Endpoint\Program\Program;
use BenTools\Qivivo\Endpoint\Program\ProgramSetting;
use BenTools\Qivivo\Message\Message;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class ProgramEndpoint {

    /**
     * Lists all programs.
     * @param $thermostatId
     * @return Message
     */
    public function getPrograms($thermostatId) {
        $request = new Request('GET', sprintf('/devices/thermostats/%s/programs', $thermostatId));
        return new Message($request, function (ResponseInterface $response) {
            $json     = \GuzzleHttp\json_decode($response->getBody(), true);
            $programs = [];
            foreach ($json['user_programs'] AS $programData) {
                $program = new Program();
                $program->setId((int) $programData['id']);
                $program->setName((string) $programData['name']);
                foreach ($programData['program'] AS $dayOfWeek => $programSettings) {
                    foreach ($programSettings AS $programSetting) {
                        $program->setSettingFor($dayOfWeek, new ProgramSetting($programSetting['period_start'], $programSetting['period_end'], $programSetting['temperature_setting']));
                    }
                }
                $program->setActive($program->getId() === (int) $json['user_active_program_id']);
                $programs[] = $program;
            }
            return $programs;
        });
    }

    /**
     * @param         $thermostatId
     * @param Program $program
     * @return Message
     */
    public function createProgram($thermostatId, Program $program) {
        $body = \GuzzleHttp\json_encode($program);
        $request = new Request('POST', sprintf('/devices/thermostats/%s/programs', $thermostatId), ['Content-Type' => 'application/json'], $body);
        return new Message($request);
    }

    /**
     * Activates the program.
     * @param $thermostatId
     * @param $programId
     * @return Message
     */
    public function activateProgram($thermostatId, $programId) {
        $request = new Request('PUT', sprintf('/devices/thermostats/%s/programs/%d/active', $thermostatId, $programId));
        return new Message($request);
    }

    /**
     * Changes the program name.
     * @param $thermostatId
     * @param $programId
     * @param $name
     * @return Message
     */
    public function changeProgramName($thermostatId, $programId, $name) {
        $body    = \GuzzleHttp\json_encode([
            'new_name' => $name,
        ]);
        $request = new Request('PUT', sprintf('/devices/thermostats/%s/programs/%d/name', $thermostatId, $programId), ['Content-Type' => 'application/json'], $body);
        return new Message($request);
    }

    /**
     * Update program settings
     * @param       $thermostatId
     * @param       $programId
     * @param       $dayOfWeek
     * @param array ...$programSettings
     * @return Message
     * @throws \InvalidArgumentException
     */
    public function updateProgramSettings($thermostatId, $programId, $dayOfWeek, ...$programSettings) {
        if (!in_array($dayOfWeek, [Program::MONDAY, Program::TUESDAY, Program::WEDNESDAY, Program::THURSDAY, Program::FRIDAY, Program::SATURDAY, Program::SUNDAY])) {
            throw new \InvalidArgumentException('Invalid $dayOfWeek');
        }
        if (count($programSettings) === 0) {
            throw new \InvalidArgumentException('At least one program setting should be set.');
        }
        foreach ($programSettings AS $programSetting) {
            if (!$programSetting instanceof ProgramSetting) {
                throw new \InvalidArgumentException(sprintf('$programSettings should be an array of %s.', ProgramSetting::class));
            }
        }
        $body    = \GuzzleHttp\json_encode([
            'program_day_update' => $programSettings,
        ]);
        $request = new Request('PUT', sprintf('/devices/thermostats/%s/programs/%d/day/%s', $thermostatId, $programId, $dayOfWeek), ['Content-Type' => 'application/json'], $body);
        return new Message($request);
    }

    /**
     * Deletes a program
     * @param $thermostatId
     * @param $programId
     * @return Message
     */
    public function deleteProgram($thermostatId, $programId) {
        $request = new Request('DELETE', sprintf('/devices/thermostats/%s/programs/%d', $thermostatId, $programId));
        return new Message($request);
    }

}
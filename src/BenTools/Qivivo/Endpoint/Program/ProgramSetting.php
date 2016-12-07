<?php

namespace BenTools\Qivivo\Endpoint\Program;

class ProgramSetting implements \JsonSerializable {

    const PRESET_1          = 'pres_1';
    const PRESET_2          = 'pres_2';
    const PRESET_3          = 'pres_3';
    const PRESET_4          = 'pres_4';
    const NIGHT             = 'nuit';
    const ABSENCE           = 'absence';
    const FREEZE_PROTECTION = 'hg';
    const COMFORT           = 'confort';

    /**
     * @var string
     */
    protected $startTime;

    /**
     * @var string
     */
    protected $endTime;

    /**
     * @var string
     */
    protected $setting;

    /**
     * ProgramSetting constructor.
     * @param string $startTime
     * @param string $endTime
     * @param string $setting
     */
    public function __construct($startTime = null, $endTime = null, $setting = null) {
        $this->startTime = $startTime;
        $this->endTime   = $endTime;
        $this->setting   = $setting;
    }

    /**
     * @return string
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     * @return $this - Provides Fluent Interface
     */
    public function setStartTime($startTime) {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * @param string $endTime
     * @return $this - Provides Fluent Interface
     */
    public function setEndTime($endTime) {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getSetting() {
        return $this->setting;
    }

    /**
     * @param string $setting
     * @return $this - Provides Fluent Interface
     */
    public function setSetting($setting) {
        $this->setting = $setting;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'period_start'        => $this->getStartTime(),
            'period_end'          => $this->getEndTime(),
            'temperature_setting' => $this->getSetting(),
        ];
    }

}
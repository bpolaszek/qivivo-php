<?php

namespace BenTools\Qivivo\Endpoint\Program;

class Program implements \JsonSerializable {

    const MONDAY    = 'lundi';
    const TUESDAY   = 'mardi';
    const WEDNESDAY = 'mercredi';
    const THURSDAY  = 'jeudi';
    const FRIDAY    = 'vendredi';
    const SATURDAY  = 'samedi';
    const SUNDAY    = 'dimanche';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ProgramSetting[]
     */
    protected $programSettings = [];

    /**
     * @var bool
     */
    protected $active;

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setMondaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::MONDAY, $programSetting);
    }

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setTuesdaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::TUESDAY, $programSetting);
    }

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setWednesdaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::WEDNESDAY, $programSetting);
    }

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setThursdaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::THURSDAY, $programSetting);
    }

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setFridaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::FRIDAY, $programSetting);
    }

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setSaturdaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::SATURDAY, $programSetting);
    }

    /**
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setSundaySetting(ProgramSetting $programSetting) {
        return $this->setSettingFor(static::SUNDAY, $programSetting);
    }

    /**
     * @param                $dayOfWeek
     * @param ProgramSetting $programSetting
     * @return $this - Provides fluent interface
     */
    public function setSettingFor($dayOfWeek, ProgramSetting $programSetting) {
        if (!in_array($dayOfWeek, [self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY])) {
            throw new \InvalidArgumentException('Invalid $dayOfWeek');
        }
        $this->programSettings[$dayOfWeek] = array_merge((array) $this->programSettings[$dayOfWeek], $programSetting);
        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this - Provides Fluent Interface
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this - Provides Fluent Interface
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this - Provides Fluent Interface
     */
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }

    /**
     * @return ProgramSetting[]
     */
    public function getProgramSettings() {
        return $this->programSettings;
    }


    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'name'    => $this->name,
            'program' => $this->programSettings,
        ];
    }
}
<?php

namespace Models\Vehicles;

use Models\Parkings\Parking;

/**
 * Base class for all vehicles which registered by state
 */
abstract class RegisteredVehicle extends Vehicle
{
    /**
     * @var string Registration number (unique) of vehicle
     */
    protected $registrationNumber;


    /**
     * @param string $registrationNumber Registration number (unique) of vehicle
     */
    public function __construct($registrationNumber)
    {
        $this->setRegistrationNumber($registrationNumber);
    }


    /**
     * @return string Registration number of vehicle
     */
    public function getRegistrationNumber()
    {
        return $this->registrationNumber;
    }
    
    /**
     * @param string $registrationNumber Registration (unique) number of vehicle
     */
    protected function setRegistrationNumber($registrationNumber)
    {
        // TODO Check uniqueness of registration number in state
        $this->registrationNumber = $registrationNumber;
    }

    /**
     * @param string $registrationNumber New registration (unique) number of vehicle
     */
    public function changeRegistrationNumber($registrationNumber)
    {
        // TODO Possible feature
    }
}

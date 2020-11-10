<?php

namespace Models\Vehicles;

use Models\Enums\VehicleTypes;

class Car extends RegisteredVehicle
{
    /**
     * @param string $registrationNumber Registration number (unique) of vehicle
     * @param boolean $isInvalid Is person with invalidity who drives a vehicle (default: false)
     */
    public function __construct($registrationNumber, $isInvalid=false)
    {
        parent::__construct($registrationNumber);
        $this->setVehicleType(
            $isInvalid ? VehicleTypes::CAR_INVALID : VehicleTypes::CAR
        );
    }


    public function isInvalid()
    {
        return $this->getVehicleType() === VehicleTypes::CAR_INVALID;
    }
}

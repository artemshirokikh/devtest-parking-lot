<?php

namespace Models\Vehicles;

use Models\Enums\VehicleTypes;

class Bus extends RegisteredVehicle
{
    /**
     * @param string $registrationNumber Registration number (unique) of vehicle
     */
    public function __construct($registrationNumber)
    {
        parent::__construct($registrationNumber);
        $this->setVehicleType(VehicleTypes::BUS);
    }
}

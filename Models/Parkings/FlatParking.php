<?php

namespace Models\Parkings;

use Models\Enums\ParkingTypes;

class FlatParking extends Parking
{
    /**
     * @param string $parkingCode Unique code of parking lot
     */
    public function __construct($parkingCode)
    {
        parent::__construct($parkingCode);
        $this->setParkingType(ParkingTypes::FLAT);
    }
}

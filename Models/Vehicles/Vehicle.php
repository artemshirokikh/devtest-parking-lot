<?php

namespace Models\Vehicles;

use Models\Enums\ParkingTypes;
use Models\Enums\VehicleTypes;
use Models\Model;

/**
 * Base class for all vehicles
 */
abstract class Vehicle extends Model
{   
    /**
     * @var string Type of vehicle
     */
    protected $vehicleType;

    /**
     * @var string Parking ID where vehicle has parked
     */
    protected $parkingId;

    /**
     * @var string Type of parking place where vehicle has parked
     * @see ParkingTypes
     */
    protected $parkingPlaceType;

    
    /**
     * @param string $vehicleType Type of vehicle
     * @see VehicleTypes
     */
    protected function setVehicleType($vehicleType)
    {
        $this->vehicleType = $vehicleType;
    }
    
    /**
     * @return string Type of vehicle
     */
    public function getVehicleType()
    {
        return $this->vehicleType;
    }

    /**
     * @return string Parking ID where vehicle has parked
     */
    public function getParkingId()
    {
        return $this->parkingId;
    }

    /**
     * @param string $parkingId Parking ID where vehicle has parked
     * @return $this
     */
    public function setParkingId($parkingId)
    {
        $this->parkingId = $parkingId;
        return $this;
    }

    public function removeParkingId()
    {
        $this->parkingId = null;
        return $this;
    }

    /**
     * @return string Type of parking place where vehicle has parked
     * @see ParkingTypes
     */
    public function getParkingPlaceType()
    {
        return $this->parkingPlaceType;
    }

    /**
     * @param string $parkingPlaceType Type of parking place where vehicle has parked
     * @return $this
     *
     * @see ParkingTypes
     */
    public function setParkingPlaceType($parkingPlaceType)
    {
        $this->parkingPlaceType = $parkingPlaceType;
        return $this;
    }

    public function removeParkPlaceType()
    {
        $this->parkingPlaceType = null;
        return $this;
    }
}

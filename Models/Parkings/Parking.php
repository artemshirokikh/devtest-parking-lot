<?php

namespace Models\Parkings;

use Models\Model;
use Models\Vehicles\Vehicle;
use Models\Collections\Managers\VehicleManager;

abstract class Parking extends Model
{   
    /**
     * @var string Type of parking
     */
    protected $parkingType;

    /**
     * @var string Unique code of parking lot
     */
    protected $parkingCode;

    /**
     * @var VehicleManager Registered vehicles at parking lot
     */
    protected $vehicleManager;


    /**
     * @param string $parkingCode Unique code of parking lot
     */
    public function __construct($parkingCode)
    {
        $this->setParkingCode($parkingCode);
    }


    /**
     * @return string Type of parking
     */
    public function getParkingType()
    {
        return $this->parkingType;
    }
    
    /**
     * @param string $parkingType Type of parking
     */
    protected function setParkingType($parkingType)
    {
        return $this->parkingType = $parkingType;
    }

    /**
     * @return string Unique code of parking lot
     */
    public function getParkingCode()
    {
        return $this->parkingCode;
    }
    
    /**
     * @param string $parkingCode Unique code of parking lot
     */
    protected function setParkingCode($parkingCode)
    {
        // TODO Check code for uniqueness in a company
        $this->parkingCode = $parkingCode;
    }

    public function setVehicleManager(VehicleManager $vehicleManager)
    {
        $this->vehicleManager = $vehicleManager;
    }

    public function parkVehicle(Vehicle $vehicle)
    {
        return $this->vehicleManager->parkVehicle($vehicle);
    }

    public function departVehicle(Vehicle $vehicle)
    {
        return $this->vehicleManager->departVehicle($vehicle);
    }

    public function getParkedVehicle($parkingId)
    {
        return $this->vehicleManager->getParkedVehicle($parkingId);
    }

    public function getParkedVehicles($vehicleType=null)
    {
        return $this->vehicleManager->getParkedVehicles($vehicleType);
    }
}

<?php

namespace Services\Builders;

use Models\Collections\Managers\RegVehicleManager;
use Models\Enums\ParkingTypes;
use Models\Parkings\FlatParking;

class ParkingBuilder extends Builder
{
    protected $parkingLot;
    protected $vehicleManager;

    protected $parkingType;
    protected $parkingCode;
    protected $parkingMode;
    protected $placesCount;


    public function reset()
    {
        $this->parkingLot = null;
        $this->vehicleManager = null;

        $this->parkingType = null;
        $this->parkingCode = null;
        $this->parkingMode = null;
        $this->placesCount = [];

        return $this;
    }

    public function setParkingType($parkingType)
    {
        $this->parkingType = $parkingType;
        return $this;
    }

    public function setParkingCode($parkingCode)
    {
        $this->parkingCode = $parkingCode;
        return $this;
    }

    public function setParkingMode($parkingMode)
    {
        $this->parkingMode = $parkingMode;
        return $this;
    }

    public function setPlacesCount($vehicleType, $placesCount)
    {
        $this->placesCount[$vehicleType] = $placesCount;
        return $this;
    }

    public function build()
    {
        if (!$this->buildParkingLot()) {
            return null;
        }
        if (!$this->buildVehicleManager()) {
            return null;
        }

        $this->parkingLot->setVehicleManager($this->vehicleManager);
        return $this->parkingLot;
    }

    protected function buildParkingLot()
    {
        if (!ParkingTypes::isValid($this->parkingType)) {
            return false;
        }

        switch ($this->parkingType) {
            case ParkingTypes::FLAT:
                $this->parkingLot = new FlatParking($this->parkingCode);
                break;
            default:
                return false;
        }

        return true;
    }

    protected function buildVehicleManager()
    {
        switch ($this->parkingType) {
            case ParkingTypes::FLAT:
                $this->vehicleManager = new RegVehicleManager();
                break;
            default:
                return false;
        }

        $this->vehicleManager->setParkingMode($this->parkingMode);
        foreach ($this->placesCount as $vehicleType => $placesCount) {
            $this->vehicleManager->setPlacesCount($vehicleType, $placesCount);
        }

        return true;
    }
}
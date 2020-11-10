<?php

namespace Services\Builders;

use Models\Enums\VehicleTypes;
use Models\Vehicles\Bus;
use Models\Vehicles\Car;
use Models\Vehicles\Motorbike;

class VehicleBuilder extends Builder
{
    protected $vehicle;

    protected $vehicleType;
    protected $registrationNumber;
    protected $parkingId;
    protected $parkingPlaceType;


    public function reset()
    {
        $this->vehicle = null;

        $this->vehicleType = null;
        $this->registrationNumber = null;
        $this->parkingId = null;
        $this->parkingPlaceType = null;

        return $this;
    }

    public function setVehicleType($vehicleType)
    {
        $this->vehicleType = $vehicleType;
        return $this;
    }

    public function setRegistrationNumber($registrationNumber)
    {
        $this->registrationNumber = $registrationNumber;
        return $this;
    }

    public function setParkingId($parkingId)
    {
        $this->parkingId = $parkingId;
        return $this;
    }

    public function setParkingPlaceType($parkingPlaceType)
    {
        $this->parkingPlaceType = $parkingPlaceType;
        return $this;
    }

    public function build()
    {
        if (!VehicleTypes::isValid($this->vehicleType)) {
            return null;
        }

        switch ($this->vehicleType) {
            case VehicleTypes::MOTORBIKE:
                $this->vehicle = new Motorbike($this->registrationNumber);
                break;
            case VehicleTypes::CAR:
                $this->vehicle = new Car($this->registrationNumber);
                break;
            case VehicleTypes::CAR_INVALID:
                $this->vehicle = new Car($this->registrationNumber, true);
                break;
            case VehicleTypes::BUS:
                $this->vehicle = new Bus($this->registrationNumber);
                break;
            default:
                return null;
        }

        $this->vehicle
            ->setParkingId($this->parkingId)
            ->setParkingPlaceType($this->parkingPlaceType);

        return $this->vehicle;
    }
}
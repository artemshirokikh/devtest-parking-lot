<?php

namespace Models\Collections\Managers;

use Models\Enums\ParkingModes;
use Models\Enums\VehicleTypes;
use Models\Vehicles\Vehicle;
use Models\Vehicles\RegisteredVehicle;

class RegVehicleManager extends VehicleManager
{
    /**
     * @param string $vehicleType Type of vehicle
     * @return string|null Type of available place to park in
     * or NULL if there is no such a place
     *
     * @see VehicleTypes
     */
    protected function getAvailablePlaceType($vehicleType)
    {
        // INFO If you want to remove this row then replace
        // it with $this->initAvailablePlaces(); at the first row
        $availablePlaces = $this->getAvailablePlaces();

        // Wrong type of vehicle
        if (!VehicleTypes::isValid($vehicleType)) {
            return null;
        }

        // There is available place for a vehicle
        if (isset($availablePlaces[$vehicleType]) && $availablePlaces[$vehicleType] > 0) {
            return $vehicleType;
        }

        // No available place for that type of vehicle
        // Then looking for another opportunity
        if (!isset($availablePlaces[$vehicleType]) || $availablePlaces[$vehicleType] <= 0)
        {
            switch ($vehicleType)
            {
                case VehicleTypes::BUS:
                    return null;

                case VehicleTypes::CAR_INVALID:
                    if (isset($availablePlaces[VehicleTypes::CAR])
                        && $availablePlaces[VehicleTypes::CAR] > 0)
                    {
                        return VehicleTypes::CAR;
                    }
                    elseif (isset($availablePlaces[VehicleTypes::BUS])
                        && $availablePlaces[VehicleTypes::BUS] > 0
                        && in_array($this->parkingMode, [
                            ParkingModes::BUS_OPTION,
                            ParkingModes::FREE
                        ])
                    ) {
                        return VehicleTypes::BUS;
                    }
                    break;

                case VehicleTypes::CAR:
                    if (isset($availablePlaces[VehicleTypes::BUS])
                        && $availablePlaces[VehicleTypes::BUS] > 0
                        && in_array($this->parkingMode, [
                            ParkingModes::BUS_OPTION,
                            ParkingModes::FREE
                        ])
                    ) {
                        return VehicleTypes::BUS;
                    }
                    elseif (isset($availablePlaces[VehicleTypes::CAR_INVALID])
                        && $availablePlaces[VehicleTypes::CAR_INVALID] > 0
                        && $this->parkingMode === ParkingModes::FREE
                    ) {
                        return VehicleTypes::CAR_INVALID;
                    }
                    break;

                case VehicleTypes::MOTORBIKE:
                    if (isset($availablePlaces[VehicleTypes::CAR])
                        && $availablePlaces[VehicleTypes::CAR] > 0
                        && in_array($this->parkingMode, [
                            ParkingModes::CAR_OPTION,
                            ParkingModes::BUS_OPTION,
                            ParkingModes::FREE
                        ])
                    ) {
                        return VehicleTypes::CAR;
                    }
                    elseif (isset($availablePlaces[VehicleTypes::BUS])
                        && $availablePlaces[VehicleTypes::BUS] > 0
                        && in_array($this->parkingMode, [
                            ParkingModes::BUS_OPTION,
                            ParkingModes::FREE
                        ])
                    ) {
                        return VehicleTypes::BUS;
                    }
                    elseif (isset($availablePlaces[VehicleTypes::CAR_INVALID])
                        && $availablePlaces[VehicleTypes::CAR_INVALID] > 0
                        && $this->parkingMode === ParkingModes::FREE
                    ) {
                        return VehicleTypes::CAR_INVALID;
                    }
                    break;

                default:
                    return null;
            }
        }

        return null;
    }

    /**
     * @param Vehicle $vehicle
     * @return bool Is is was parked or not
     */
    public function parkVehicle(Vehicle $vehicle)
    {
        if ($vehicle === null) {
            return false;
        }

        $availablePlaceType = $this->getAvailablePlaceType($vehicle->getVehicleType());
        if ($availablePlaceType === null) {
            return false;
        }

        $this->decAvailablePlaces($availablePlaceType);

        $parkingId = $vehicle instanceof RegisteredVehicle
            ? $vehicle->getRegistrationNumber()
            : rand(1, 9999);

        $this->addMapItem($parkingId, [
            'vehicle' => $vehicle,
            'parkingPlaceType' => $availablePlaceType,
            'parkedAt' => date('Y-m-d H:i:s')
        ]);
        $vehicle->setParkingId($parkingId)
            ->setParkingPlaceType($availablePlaceType);

        return true;
    }

    /**
     * @param Vehicle $vehicle
     * @return bool Is it was departed or not
     */
    public function departVehicle(Vehicle $vehicle)
    {
        if ($vehicle === null || $vehicle->getParkingId() === null) {
            return false;
        }

        $mapItem = $this->removeMapItem($vehicle->getParkingId());
        $placeType = $mapItem['parkingPlaceType'];

        $this->incAvailablePlaces($placeType);
        $vehicle->removeParkingId()
            ->removeParkPlaceType();

        return true;
    }

    public function getParkedVehicle($parkingId)
    {
        $mapItem = $this->getMapItem($parkingId);
        return isset($mapItem['vehicle'])
            ? $mapItem['vehicle']
            : null;
    }

    /**
     * @param string|null $vehicleType Filter by vehicle type (NOT IMPLEMENTED)
     * @return array {
     *      List with parked registered by state vehicles
     *
     *      @type array {
     *          Array with vehicle info
     *
     *          @type RegisteredVehicle Registereg by state vehicle
     *          @type string Parking place type (@see VehicleTypes)
     *          @type string Parked date and time
     *      }
     */
    public function getParkedVehicles($vehicleType=null)
    {
        // TODO Add possibility to filter by vehicle type
        $map = $this->getMap();
        return array_values($map);
    }
}
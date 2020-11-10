<?php

namespace Models\Collections\Managers;

use Models\Enums\ParkingModes;
use Models\Enums\VehicleTypes;
use Models\Vehicles\Vehicle;

class VehicleManager extends CollectionManager
{
    /**
     * @var string Where vehicles could park at parking lot
     */
    protected $parkingMode;

    /**
     * @var array {
     *      Count of places at parking lot by vehicle type (IMMUTABLE)
     *
     *      @type string Type of vehicle
     *      @type integer Count of places at parking lot
     * }
     */
    protected $placesCount;

    /**
     * @var array {
     *      Count of places at parking lot by vehicle type
     *
     *      @type string Type of vehicle
     *      @type integer Available count of places at parking lot
     * }
     */
    protected $availablePlaces;


    public function __construct()
    {
        $this->placesCount = [];
        $this->reset();
    }


    /**
     * Reset internal collections and counters
     * @return $this
     */
    public function reset()
    {
        $this->availablePlaces = null;

        $this->list = [];
        $this->map = [];

        return $this;
    }

    /**
     * @return string Parking mode
     */
    public function getParkingMode()
    {
        return $this->parkingMode;
    }

    /**
     * @param string $parkingMode Parking mode
     * @return $this
     *
     * @see ParkingModes
     */
    public function setParkingMode($parkingMode)
    {
        if (ParkingModes::isValid($parkingMode)) {
            $this->parkingMode = $parkingMode;
        }
        return $this;
    }

    /**
     * @param string|null $vehicleType Vehicle type for parking places count
     * @return array|integer|null
     *      - Array with all counts of places by vehicle type if passed null
     *      - Count of places if passed existing vehicle type
     *      - NULL if vehicle type is missing or wrong
     *
     * @see VehicleTypes
     */
    public function getPlacesCount($vehicleType=null)
    {
        if ($vehicleType === null) {
            return $this->placesCount;
        }
        if (!VehicleTypes::isValid($vehicleType)
            || !array_key_exists($vehicleType, $this->placesCount)
        ) {
            return null;
        }
        return $this->placesCount[$vehicleType];
    }

    /**
     * @param string $vehicleType Vehicle type
     * @param int $placesCount Count of places at parking lot
     * @return $this
     *
     * @see VehicleTypes
     */
    public function setPlacesCount($vehicleType, $placesCount)
    {
        if (!VehicleTypes::isValid($vehicleType)
            || !(is_int($placesCount) && $placesCount >= 0)
        ) {
            return $this;
        }

        $this->placesCount[$vehicleType] = $placesCount;

        return $this;
    }

    /**
     * Initiate counters of available places
     * @return $this
     */
    protected function initAvailablePlaces()
    {
        if ($this->availablePlaces === null) {
            $this->availablePlaces = $this->placesCount;
        }
        return $this;
    }

    /**
     * @param string|null $vehicleType Vehicle type for available parking places count
     * @return array|integer|null
     *      - Array with all counts of available places by vehicle type if passed null
     *      - Count of available places if passed existing vehicle type
     *      - NULL if vehicle type is missing or wrong
     *
     * @see VehicleTypes
     */
    public function getAvailablePlaces($vehicleType=null)
    {
        $this->initAvailablePlaces();

        if ($vehicleType === null) {
            return $this->availablePlaces;
        }
        if (!VehicleTypes::isValid($vehicleType)
            || !array_key_exists($vehicleType, $this->availablePlaces)
        ) {
            return null;
        }
        return $this->availablePlaces[$vehicleType];
    }

    /**
     * @param string $vehicleType Type of vehicle
     * @return bool Is there available place to park in or not
     *
     * @see VehicleTypes
     */
    public function existAvailablePlaces($vehicleType)
    {
        return $this->getAvailablePlaceType($vehicleType) !== null;
    }

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

        return null;
    }

    /**
     * @param string $vehicleType Type of vehicle to depart
     * @return $this
     *
     * @see VehicleTypes
     */
    protected function incAvailablePlaces($vehicleType)
    {
        $this->initAvailablePlaces();

        if (VehicleTypes::isValid($vehicleType)
            && array_key_exists($vehicleType, $this->availablePlaces)
        ) {
            $this->availablePlaces[$vehicleType]++;
        }
        return $this;
    }

    /**
     * @param string $vehicleType Type of vehicle to park
     * @return $this
     *
     * @see VehicleTypes
     */
    protected function decAvailablePlaces($vehicleType)
    {
        $this->initAvailablePlaces();

        if (VehicleTypes::isValid($vehicleType)
            && array_key_exists($vehicleType, $this->availablePlaces)
        ) {
            $this->availablePlaces[$vehicleType]--;
        }
        return $this;
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
        $parkingId = $this->addListItem([
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

        $listItem = $this->removeListItem($vehicle->getParkingId());
        $placeType = $listItem['parkingPlaceType'];

        $this->incAvailablePlaces($placeType);
        $vehicle->removeParkingId()
            ->removeParkPlaceType();

        return true;
    }

    public function getParkedVehicle($parkingId)
    {
        // TODO Implement
    }

    /**
     * @param string|null $vehicleType Filter by vehicle type (NOT IMPLEMENTED)
     * @return array {
     *      List with parked vehicles
     *
     *      @type array {
     *          Array with vehicle info
     *
     *          @type Vehicle ehicle
     *          @type string Parking place type (@see VehicleTypes)
     *          @type string Parked date and time
     *      }
     */
    public function getParkedVehicles($vehicleType=null)
    {
        // TODO Add possibility to filter by vehicle type
        return $this->getList();
    }
}
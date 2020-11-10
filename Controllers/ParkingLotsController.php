<?php

namespace Controllers;

use Models\Enums\BuilderTypes;
use Models\Enums\HttpCodes;
use Models\Enums\RequestMethods;
use Models\Parkings\Parking;
use Services\Configuration;
use Services\Persistence\Persistence;
use Services\Router;

class ParkingLotsController extends RestApiController
{
    protected $parkingLotTable;

    /**
     * @var Parking
     */
    protected $parkingLot;


    public function __construct(Configuration $config, Router $router, Persistence $persist)
    {
        parent::__construct($config, $router, $persist);
        $this->parkingLotTable = $this->config->persistence['parkingLot']['table'];
    }


    public function processRequest()
    {
        if ($this->isEmptyParkingTable()) {
            $this->initParkingTable();
        }
        $parkingLotId = $this->router->getObjectId();
        $parkingLotId = $this->router->transformId($parkingLotId);
        $this->loadParkingLot($parkingLotId);

        $subObject = $this->router->getSubObject();
        $subObject = $this->router->transformName($subObject);

        $response = null;
        switch ($subObject) {
            case 'Vehicles': // TODO Create enumeration class for these
                $response = $this->processVehiclesRequest();
                break;

            default:
                $response = $this->response(HttpCodes::HTTP_404);
        }
        $this->router->respond($response);
    }

    protected function processVehiclesRequest()
    {
        $response = null;
        switch ($this->router->getMethod()) {
            case RequestMethods::GET:
                $response = $this->getVehicles();
                break;
            case RequestMethods::POST:
                $response = $this->parkVehicle($this->router->getInput());
                break;
            case RequestMethods::DELETE:
                $parkingId = $this->router->getSubObjectId();
                $parkingId = $this->router->transformId($parkingId);
                $response = $this->departVehicle($parkingId);
                break;

            default:
                $response = $this->response(HttpCodes::HTTP_404);
        }
        return $response;
    }

    protected function isEmptyParkingTable()
    {
        return $this->persist->isEmptyTable($this->parkingLotTable);
    }

    protected function initParkingTable()
    {
        $initConfig = $this->config->persistence['parkingLot']['initialization'];

        $this->builders[BuilderTypes::PARKING_LOT]
            ->reset()
            ->setParkingType($initConfig['parkingLot']['parkingType'])
            ->setParkingCode($initConfig['parkingLot']['parkingCode'])
            ->setParkingMode($initConfig['vehicleManager']['parkingMode']);

        $placesCountMap = $initConfig['vehicleManager']['placesCount'];
        foreach ($placesCountMap as $vehicleType => $placesCount) {
            $this->builders[BuilderTypes::PARKING_LOT]
                ->setPlacesCount($vehicleType, $placesCount);
        }

        $parkingLot = $this->builders[BuilderTypes::PARKING_LOT]->build();
        if (!isset($parkingLot)) {
            // WE CANNOT WORK WITH INITIALIZED PARKING LOT
            $this->router->respondNotFound(true); // TODO Throw another error (check config)
        }

        $this->persist->insert(
            $this->parkingLotTable,
            $parkingLot->getParkingCode(),
            $parkingLot
        );
    }

    protected function loadParkingLot($parkingLotId)
    {
        $this->parkingLot = $this->persist->select($this->parkingLotTable, $parkingLotId);
        if (!isset($this->parkingLot)) {
            // WE CANNOT WORK WITH INITIALIZED PARKING LOT
            $this->router->respondNotFound(true);
        }
    }

    protected function getVehicles()
    {
        $parkedVehicles = $this->parkingLot->getParkedVehicles();

        $output = [];
        foreach ($parkedVehicles as $item) {
            $output[] = [
                'id' => $item['vehicle']->getParkingId(),
                'registrationNumber' => $item['vehicle']->getRegistrationNumber(),
                'vehicleType' => $item['vehicle']->getVehicleType(),
                'parkingPlaceType' => $item['parkingPlaceType'],
                'parkedAt' => $item['parkedAt'],
            ];
        }
        return $this->response(HttpCodes::HTTP_200, $output);
    }

    protected function parkVehicle($input)
    {
        if (!$this->isValidVehicle($input)) {
            return $this->response(HttpCodes::HTTP_422, ['error' => 'Invalid input']);
        }

        $vehicle = $this->builders[BuilderTypes::VEHICLE]
            ->reset()
            ->setVehicleType($input['vehicleType'])
            ->setRegistrationNumber($input['registrationNumber'])
            ->build();
        if (!isset($vehicle)) {
            return $this->response(HttpCodes::HTTP_422, ['error' => 'Invalid input']);
        }

        if (!$this->parkingLot->parkVehicle($vehicle)) {
            return $this->response(HttpCodes::HTTP_422, ['error' => 'No free parking places']);
        }
        $this->persist->update(
            $this->parkingLotTable,
            $this->parkingLot->getParkingCode(),
            $this->parkingLot
        );

        return $this->response(HttpCodes::HTTP_201, [
            'id' => $vehicle->getParkingId(),
            'registrationNumber' => $vehicle->getRegistrationNumber(),
            'vehicleType' => $vehicle->getVehicleType(),
            'parkingPlaceType' => $vehicle->getParkingPlaceType(),
        ]);
    }

    protected function isValidVehicle($input)
    {
        if (isset($input['vehicleType']) && isset($input['registrationNumber'])) {
            return true;
        }
        return false;
    }

    protected function departVehicle($parkingId)
    {
        $vehicle = $this->parkingLot->getParkedVehicle($parkingId);
        if (!isset($vehicle)) {
            return $this->response(HttpCodes::HTTP_404);
        }

        if (!$this->parkingLot->departVehicle($vehicle)) {
            return $this->response(HttpCodes::HTTP_422, ['error' => 'Unknown error']);
        }
        $this->persist->update(
            $this->parkingLotTable,
            $this->parkingLot->getParkingCode(),
            $this->parkingLot
        );

        return $this->response(HttpCodes::HTTP_200);
    }

    protected function response($status, $body=null)
    {
        $response = ['status' => $status];
        if (isset($body)) {
            $response['body'] = $body;
        }
        return $response;
    }
}
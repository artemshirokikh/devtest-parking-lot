<?php

return array(
    'name' => 'Parking System',
    'version' => '0.0.1',

    'persistence' => [
        'class' => 'Services\Persistence\SerializablePersistence',
        'connection' => 'serialized.data',

        'parkingLot' => [
            'table' => 'parkingLots',
            'initialization' => [
                'parkingLot' => [
                    'parkingType' => 'flat',
                    'parkingCode' => 'F1',
                ],
                'vehicleManager' => [
                    'parkingMode' => 'car',
                    'placesCount' => [
                        'motorbike' => 2,
                        'car' => 2,
                        'bus' => 2,
                    ],
                ],
            ],
        ],
    ],
);

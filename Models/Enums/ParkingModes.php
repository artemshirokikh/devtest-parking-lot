<?php

namespace Models\Enums;

class ParkingModes extends Enumeration
{
    /**
     * Each type of vehicle must be parked on own type of place only
     */
    const STRICT = 'strict';

    /**
     * Buses and cars must be parked on own type of place only. Motorbikes
     * could be parked on their own places and on car places as well (except
     * places for invalid cars).
     */
    const CAR_OPTION = 'car';

    /**
     * Buses must be parked on own type of place only. Motorbikes and cars
     * could be parked on their own places and on car and bus places as well
     * (except places for invalid cars).
     */
    const BUS_OPTION = 'bus';

    /**
     * Each type of vehicle could be parked on every free place
     */
    const FREE = 'free';
}

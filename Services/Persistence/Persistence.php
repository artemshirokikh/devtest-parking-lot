<?php

namespace Services\Persistence;

use Services\Service;

abstract class Persistence extends Service
{
    /**
     * @var string Connection string to connect to persistence service
     */
    protected $connection;


    /**
     * @param string $connection Connection string to connect to persistence service
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     * @return Persistence
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }
}
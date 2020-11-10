<?php

namespace Controllers;

use Services\Builders\Builder;
use Services\Configuration;
use Services\Persistence\Persistence;
use Services\Router;

abstract class Controller
{
    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Persistence
     */
    protected $persist;

    /**
     * @var array {
     *      Map of builders
     *
     *      @type string Builder's name (@see )
     *      @type Builder Builder instance
     * }
     */
    protected $builders;


    public function __construct(Configuration $config, Router $router, Persistence $persist)
    {
        $this->config = $config;
        $this->router = $router;
        $this->persist = $persist;

        $this->builders = [];
    }

    /**
     * @param string $name
     * @param Builder $builder
     * @return Controller
     */
    public function setBuilder($name, $builder)
    {
        $this->builders[$name] = $builder;
        return $this;
    }
}
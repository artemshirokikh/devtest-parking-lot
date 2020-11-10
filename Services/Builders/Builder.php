<?php

namespace Services\Builders;

use Services\Service;

abstract class Builder extends Service
{
    abstract public function reset();

    abstract public function build();
}
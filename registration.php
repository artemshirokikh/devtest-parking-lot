<?php

spl_autoload_register(function ($classFullName) {
    $classFullName = str_replace('\\', '/', $classFullName);
    require_once $classFullName . '.php';
});

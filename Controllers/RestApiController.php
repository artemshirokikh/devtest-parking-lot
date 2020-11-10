<?php

namespace Controllers;

abstract class RestApiController extends Controller
{
    abstract public function processRequest();
}
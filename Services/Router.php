<?php

namespace Services;

use Models\Enums\HttpCodes;

class Router extends Service
{
    protected $uri;
    protected $method;
    protected $input;

    protected $object;
    protected $objectId;
    protected $subObject;
    protected $subObjectId;


    public function __construct()
    {
        $this->loadData();
        $this->parseData();
    }


    protected function loadData()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        $this->uri = $uri;

        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->input = (array) json_decode(file_get_contents('php://input'), TRUE);
    }

    protected function parseData()
    {
        if (isset($this->uri[1])) {
            $this->object = $this->uri[1];
            if (isset($this->uri[2])) {
                $this->objectId = $this->uri[2];
            }
        }

        if (isset($this->uri[3])) {
            $this->subObject = $this->uri[3];
            if (isset($this->uri[4])) {
                $this->subObjectId = $this->uri[4];
            }
        }
    }

    public function setCommonHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public function respondNotFound($exit=false)
    {
        $this->setCommonHeaders();
        header(HttpCodes::HTTP_404);
        if ($exit) {
            exit;
        }
    }

    public function respond(array $response, $jsonEncode=true)
    {
        $this->setCommonHeaders();
        header($response['status']);
        if (isset($response['body'])) {
            $body = $jsonEncode
                ? json_encode($response['body'])
                : $response['body'];
            echo $body;
        }
    }

    public function transformName($name)
    {
        if ($name === null) {
            return null;
        }

        $trans = '';
        $splitted = explode('_', $name);
        foreach ($splitted as $item) {
            $trans .= ucfirst($item);
        }
        return $trans;
    }

    public function transformId($id)
    {
        if ($id === null) {
            return null;
        }
        return strtoupper($id);
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getObjectId()
    {
        return $this->objectId;
    }

    public function getSubObject()
    {
        return $this->subObject;
    }

    public function getSubObjectId()
    {
        return $this->subObjectId;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getInput()
    {
        return $this->input;
    }
}
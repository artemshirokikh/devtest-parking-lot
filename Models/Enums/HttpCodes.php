<?php

namespace Models\Enums;

class HttpCodes extends Enumeration
{
    const HTTP_200 = 'HTTP/1.1 200 OK';
    const HTTP_201 = 'HTTP/1.1 201 Created';
    const HTTP_404 = 'HTTP/1.1 404 Not Found';
    const HTTP_422 = 'HTTP/1.1 422 Unprocessable Entity';
}
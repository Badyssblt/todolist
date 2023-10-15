<?php

namespace Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message = "Cette route n'existe pas";
}
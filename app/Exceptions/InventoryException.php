<?php

namespace App\Exceptions;

use Exception;

class InventoryException extends Exception
{
    protected $errorCode;

    public function __construct($message = "Inventory error occurred", $code = 0, $errorCode = 400, Exception $previous = null)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}

<?php

class ValidationException extends Exception
{

    private $errors = array();

    public function __construct(array $errors, $message = NULL)
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


}
<?php
namespace Onlyongunz\CASMinMin;

class IdentityNotSetException extends \Exception {
    public function __construct($message="", $code=0){
        parent::__construct($message, $code);
    }
}
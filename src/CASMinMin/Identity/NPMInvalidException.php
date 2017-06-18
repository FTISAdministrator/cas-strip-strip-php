<?php
namespace Chez14\CASMinMin\Identity;

class NPMInvalidException extends \Exception {
    public function __construct($message="", $code=0){
        parent::__construct($message, $code);
    }
}
<?php
namespace Onlyongunz\CASMinMin\Identity;

class Generic implements Identity{
    protected
        $username=null,
        $password=null;
    
    public function __construct($username, $password=null){
        $this->username=$username;
        $this->password=$password;
    }

    public function get_username(){
        return $this->username;
    }
    
    public function get_password() {
        return $this->password;
    }
}
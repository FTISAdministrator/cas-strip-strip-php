<?php
namespace Onlyongunz\CASMinMin\Identity;

class NPM implements IdentityBase{
    
    const
        NPM_PATTERN = '/^(20|19)([0-9]{2})([1-9]{2})0([0-9]{3})$/',
        USERNAME_SUFFIX="@student.unpar.ac.id";

    protected
        $username=null,
        $password=null,
        $npm=null;
    
    public function __construct($npm, $password=null){
        if(is_numeric($npm))
            $npm = (string)$npm;
        $this->npm=$npm;
        $matches=[];
        if(!preg_match_all(self::NPM_PATTERN, $npm, $matches))
            throw new NPMInvalidException("NPM yang diberikan tidak valid");
        $this->username = sprintf("%d%d%s",$matches[3][0], $matches[2][0], $matches[4][0]) . self::USERNAME_SUFFIX;
        $this->password=$password;
    }

    public function get_username(){
        return $this->username;
    }
    
    public function get_password(){
        return $this->password;
    }

    public function __toString(){
        return sprintf("%s:%s", $this->username, $this->password);
    }
}
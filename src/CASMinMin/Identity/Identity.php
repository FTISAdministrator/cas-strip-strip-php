<?php
namespace Onlyongunz\CASMinMin\Identity;

interface Identity {    
    /**
     * Username untuk login
     */
    public function get_username();
    
    /**
     * ambil password jika diperlukan untuk login
     */
    public function get_password();
}
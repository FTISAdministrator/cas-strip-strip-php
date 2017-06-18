<?php
namespace Chez14\CASMinMin\Identity;

interface IdentityBase {    
    /**
     * Username untuk login
     */
    public function get_username();
    
    /**
     * ambil password jika diperlukan untuk login
     */
    public function get_password();
}
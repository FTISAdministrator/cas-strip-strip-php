<?php
namespace Onlyongunz\CASMinMin;


class CASMinMin {
    const
        CAS_URL = "https://cas.unpar.ac.id/login";
    
    protected
        $service = null,
        $guzzleClient = null;

    public
        $identity = null;

    public function __construct(Services\Base $service=null) {
        if($service == null){
            $service = new Services\StudentPortal();
        }
        $this->service = $service;
    }

    public function doLogin(Identity\Identity $identity) {
        
    }
}
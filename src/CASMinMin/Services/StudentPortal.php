<?php
namespace Onlyongunz\CASMinMin\Services;
class StudentPortal implements Services\ServiceBase {
    const
        BASE_URL="https://studentportal.unpar.ac.id/",
        IGNITE_URL="home/index.login.submit.php";
    
    protected
        $client=null;

    public function init_login(){
        if($this->client == null)
            $this->client = new \GuzzleHttp\Client(self::IGNITE_URL);
        return $this->client->request('POST', IGNITE_URL, [
            'form_data'>[
                'Submit'=>'Login'
            ]
        ]);
    }

    public function done_login($ticket_url) {
        $resp = $this->client->request('GET', $ticket_url);
        //validate response.
    }

}
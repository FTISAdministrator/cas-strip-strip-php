<?php
namespace Onlyongunz\CASMinMin\Services;
use \Onlyongunz\CASMinMin\CASMinMin;

class StudentPortal implements ServiceBase {
    const
        BASE_URL="https://studentportal.unpar.ac.id/",
        IGNITE_URL="home/index.login.submit.php";
    
    const
        GUZZLE_SETTING=[
            'base_uri' => self::BASE_URL,
            'cookies' => true,
            'allow_redirects' => [
                'max'             => 5,
                'strict'          => false,
                'referer'         => true,
                'protocols'       => ['https'],
                'track_redirects' => false
            ],
            'headers' => [
                'User-Agent' => CASMinMin::USER_AGENT
            ]
        ];
    
    protected
        $client=null;

    public function init_login(){
        if($this->client == null)
            $this->client = new \GuzzleHttp\Client(self::GUZZLE_SETTING);
        return $this->client->request('POST', self::IGNITE_URL, [
            'form_data'>[
                'Submit'=>'Login'
            ]
        ]);
    }

    public function done_login($ticket) {
        $resp = $this->client->request('GET',self::IGNITE_URL, [
            'query'=>[
                'ticket'=>$ticket
            ]
        ]);
        // implement api disini
    }

    public function get_client(){
        return $this->client;
    }

    public function get_service() {
        return self::BASE_URL . self::IGNITE_URL;
    }
}
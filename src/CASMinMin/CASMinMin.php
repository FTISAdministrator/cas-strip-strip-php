<?php
namespace Onlyongunz\CASMinMin;


class CASMinMin {
    protected const
        BASE_URL = "https://cas.unpar.ac.id/",
        CAS_LOGIN = "login",
        CAS_LOGOUT = "logout";
    
    protected const
        LTPATTERN = '/<input(.*?)name="lt"(.*?)value="([a-zA-Z0-9-]+)" \/>/',
        EXPATTERN = '/<input(.*?)name="execution"(.*?)value="([a-zA-Z0-9-]+)" \/>/',
        ERRPATTERN = '/The credentials you provided cannot be determined to be authentic\./i',
        SUCPATTERN = '/<h2>Log In Successful<\/h2>/i';

    public const
        USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
    
    protected const
        GUZZLE_SETTING=[
            'base_uri' => self::BASE_URL,
            'allow_redirects' => false,
            'headers' => [
                'User-Agent' => self::USER_AGENT
            ],
            'cookies' => true
        ];

    protected
        $service = null,
        $guzzleClient = null,
        $cookieJar = null;

    public
        $identity = null;

    public function __construct(Services\ServiceBase $service=null, Identity\IdentityBase $identity) {
        if($service == null){
            $service = new Services\StudentPortal();
        }
        $this->service = $service;
        $this->identity = $identity;
    }

    public function login_identity(Identity\Identity $identity=null) {
        // preparation
        if($this->identity == null && $identity == null)
            throw new IdentityNotSetException("Identitas belum di set.");
        if($identity!=null)
            $this->identity=$identity;
        $identity=$this->identity;
        
        $this->guzzleClient = new \GuzzleHttp\Client(self::GUZZLE_SETTING);
        $client = $this->guzzleClient;

        // make session, save it to query
        $resp = $client->request('GET', self::CAS_LOGIN);
        $lt_match = [];
        preg_match_all(self::LTPATTERN, $resp->getBody(), $lt_match);
        $ex_match = [];
        preg_match_all(self::EXPATTERN, $resp->getBody(), $ex_match);

        // build query, then fetch it
        $resp = $client->request('POST', self::CAS_LOGIN, [
            'form_params'=> [
                'username'  => $this->identity->get_username(),
                'password'  => $this->identity->get_password(),
                'lt'        => $lt_match[3][0],
                'execution' => $ex_match[3][0],
                '_eventId'  => 'submit',
                'submit'    => 'LOGIN'
            ]
        ]);

        //error checking
        if(preg_match(self::ERRPATTERN, $resp->getBody()))
            throw new IdentityInvalidException('Password Salah');
    }

    public function login_service(Services\Service $service=null){
        if($this->guzzleClient==null)
            $this->login_identity();
        $client = $this->guzzleClient;

        if($service!=null)
            $this->service=$service;
        $service=$this->service;

        $service->init_login();
        $resp = $client->request("GET", self::CAS_LOGIN, [
            'query'=>[
                'service'=> $service->get_service()
            ]
        ]);

        parse_str(parse_url($resp->getHeader("Location")[0], PHP_URL_QUERY), $queries);
        $service->done_login($queries);
    }

    public function login(Services\Service $service=null){
        //if($service!=null)
            $this->login_service($service);
    }
}
<?php
namespace Onlyongunz\CASMinMin;

/**
 * CASMinMin; CAS UNPAR interface buat program.
 * 
 * @author Gunawan Christianto
 * @package Onlyongunz/CASMinMin
 * @link https://github.com/ftis-admin/cas-min-min-php
 */


/**
 * Kelas utama yang akan membantu proses login.
 * 
 * @package Onlyongunz/CASMinMin
 * @example
 * ```php
 * use Onlyongunz\CASMinMin;
 *
 * $service = new CASMinMin\Services\StudentPortal();
 * $identity = new CASMinMin\Identity\NPM('2016730011', 'Passwordku123');
 * $cas = new CASMinMin\CASMinMin($service, $identity);
 * try {
 *    $cas->login();   
 * } catch (CASMinMin\Exception\IdentityInvalidException) {
 *    echo "Salah password!";
 * }
 * ```
 * 
 * @method void login_identity(CASMinMin\Identity\IdentityBase $identity = null)
 * @method void login_service(CASMinMin\Services\ServiceBase $service = null)
 */
class CASMinMin {
    protected const
        BASE_URL = "https://sso.unpar.ac.id/",
        CAS_LOGIN = "login",
        CAS_LOGOUT = "logout";
    
    protected const
        LTPATTERN = '/<input type="hidden" name="lt" value="([a-zA-Z0-9-]+)" \/>/',
        EXPATTERN = '/<input type="hidden" name="execution" value="([\w\-\_\/\+\=]+)" \/>/',
        ERRPATTERN = '/(The credentials you provided cannot be determined to be authentic|Invalid credentials)\./i',
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

    /**
     * Class Constructor
     * @param $service default service yang akan di handlekan loginya.
     * @param $identity identitas default yang akan digunakan untuk meloginkan service.
     */
    public function __construct(Services\ServiceBase $service=null, Identity\IdentityBase $identity) {
        if($service == null){
            $service = new Services\StudentPortal();
        }
        $this->service = $service;
        $this->identity = $identity;
    }

    /**
     * Meloginkan CAS dengan identitas pengguna
     * Identitas lama yang telah di loginkan akan di hilangkan setelah anda memanggil method ini.
     * Dengan kata lain, saat anda login dengan identitas yang baru, akun sebelumnya akan di logout
     * lalu kita akan loginkan dengan akun yang baru.
     *
     * @param $identity identitas yang akan di loginkan, opsional.
     * @throws Exception\IdentityNotSetException
     *
     * @example
     * ```php
     * $identity = new CASMinMin\Identity\Generic('namadosen@unpar.ac.id', 'Passwordku123');
     * $cas = new CASMinMin\CASMinMin();
     * $cas->login_identity($identity);
     * ```
     */
    public function login_identity(Identity\Identity $identity=null) {
        // preparation
        if($this->identity == null && $identity == null)
            throw new Exception\IdentityNotSetException("Identitas belum di set.");
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
        echo "DUMP : ".PHP_EOL;
        var_dump($lt_match);
        var_dump($ex_match);
        echo "END DUMP;".PHP_EOL;
        // build query, then fetch it
        $resp = $client->request('POST', self::CAS_LOGIN, [
            'form_params'=> [
                'username'  => $this->identity->get_username(),
                'password'  => $this->identity->get_password(),
                'lt'        => $lt_match[1][0],
                'execution' => $ex_match[1][0],
                '_eventId'  => 'submit',
                'submit'    => 'LOGIN'
            ]
        ]);

        //error checking
        if(preg_match(self::ERRPATTERN, $resp->getBody()))
            throw new Exception\IdentityInvalidException('Password Salah');
    }

    /**
     * Loginkan services.
     * Method ini akan meloginkan services yang akan anda gunakan.
     * 
     * @param $service service yang akan kita loginkan
     */
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
        $service->done_login($queries['ticket']);
    }

    /**
     * Lakukan login ke CAS(jika belum) lalu ke Service.
     * Shortcode untuk login_services
     * @param $service service yang akan di loginkan.
     */
    public function login(Services\Service $service=null){
        $this->login_service($service);
    }
}
<?php

require_once __DIR__.'/Google/vendor/autoload.php';

class Google {
    protected $_service = array();
    public $client;

	public $_scopes = array(

        // calendar // Manage your calendars
        'https://www.googleapis.com/auth/calendar', 
        'https://www.googleapis.com/auth/calendar.readonly',

        // profile
        "https://www.googleapis.com/auth/plus.login",
        "https://www.googleapis.com/auth/userinfo.email",
        "https://www.googleapis.com/auth/userinfo.profile",
        "https://www.googleapis.com/auth/plus.me",

        // people contacts // Manage your contacts
        /*"https://www.googleapis.com/auth/contacts",
        "https://www.googleapis.com/auth/contacts.readonly",*/


        // "https://www.googleapis.com/auth/apps.licensing",
        // "https://www.googleapis.com/auth/appsmarketplace.license",

        // Domain
        // "https://www.googleapis.com/auth/admin.directory.domain",
        // "https://www.googleapis.com/auth/admin.directory.domain.readonly",
    );

	public function __construct() {
        $this->client = new Google_Client();
        $this->client->setAuthConfig( __DIR__. '/Google/client_secret.json');


        $this->client->isAccessTokenExpired(true);
        
        $this->client->setAccessType("offline");
        $this->client->setIncludeGrantedScopes(true);   // incremental auth
        
        /*
        $this->client->getRefreshToken();*/

        // $this->client->setApprovalPrompt("force");

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $this->client->setHttpClient($guzzleClient);
    }

    // loadAPI
    public function app( $name ) {

        $clsName = "g".ucfirst($name);
        $path = __DIR__."/Google/{$clsName}.php";
        
        if(!array_key_exists($name, $this->_service) && file_exists($path)){
            require_once $path;
            $this->_service[$name] = new $clsName();
        }
        return $this->_service[$name];
    }

    public function setAuth()
    {
        if( isset($_GET['code']) ){
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
        }
    }


    /*public function redirect()
    {
        return $this;
    }
    public function route($uri)
    {
        $this->client->setRedirectUri( URL . 'auth/google_oauth2/' );
        return $this->client->createAuthUrl();
    }*/
}
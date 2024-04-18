<?php

class Environment {

    private $LOCAL = "LOCAL";
    private $TEST = "TEST";
    private $PROD = "PROD_US";
    private $environment = "PROD-US";

    //By default it points all to LOCAL
    function __construct() {
        $loc_env = $_SERVER['DEPLOY_ENVIRONMENT'];

        if (isset($loc_env) && $loc_env !== FALSE) {
            $this->environment = $loc_env;
        }
    }
    // google social login     
    public function getGoogleRedirect() {
        if ($this->isTest()) {
            return 'https://my.test.scanour.menu/auth/login_google';
        } else {
            return 'https://my.menuflow.com/auth/login_google';
        }
    }
    
    // google social login  
    public function getAppleRedirect() {
        if ($this->isTest()) {
            return 'https://my.test.scanour.menu/auth/login_apple';
        } else {
            return 'https://my.menuflow.com/auth/login_apple';
        }
    }
    
    // google social login  
    public function getFacebookRedirect() {
        if ($this->isTest()) {
            return 'https://my.test.scanour.menu/auth/login_facebook';
        } else {
            return 'https://my.menuflow.com/auth/login_facebook';
        }
    }

    public function getApiKey() {
        return 'MF3VVbvFghSWdCAP39Qqdj33dfhljDDv7o99q2APP';
    }

    public function getGoApiKey() {
        return 'MF3VVbvFghSWdCAP39Qqdj33dfhljDDv7o99q2APP';
    }
    
    public function getOSApiKey() {
        return 'BigF7pjdE5MTfDSIsaHWZ8yTtcEzrqHK33gVneaH';
    }

    public function getApiServer() {
        return 'https://api.menuflow.dev/go'; // route to self for performance
    }

    public function getGoApiServer() {
        return 'https://api.menuflow.dev/go'; // route to self for performance
    }
    
    public function getGoApiDevServer() {
        return 'https://api.menuflow.dev/go'; // route to self for performance
    }

    public function getOSApiServer() {
        return 'https://api.orderstation.io/v1'; // route to domain as codebase on different machine
    }

    public function getMenuServer() {
        return 'https://menu.menuflow.com';
    }

    public function getCrdServer() {
        return 'https://crd.menuflow.com';
    }

    public function getWebServer() {
        return 'https://menuflow.com';
    }

    public function getAdminServer() {
        return 'https://admin.menuflow.com';
    }

    public function getMyServer() {
        return 'https://my.menuflow.com';
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function isTest() {
        return $this->environment == $this->TEST;
    }

    public function isLocal() {
        return $this->environment == $this->LOCAL;
    }

    //Move secrets to secret manager
    public function getDB() {
        $default_host = 'menuflow-prod-us.cycbtuc2z4t9.us-east-1.rds.amazonaws.com';
        $default_user = 'menuflow';
        $default_pass = 'C0ldm1lk$$';
        $default_database = 'menuflow-go';
        $db_port = 3306;
        return new mysqli($default_host, $default_user, $default_pass, $default_database, $db_port);
    }
}
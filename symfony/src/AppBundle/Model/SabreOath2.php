<?php

namespace AppBundle\Model;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\FilesystemCache;



class SabreOath2 {

    const BASE_URI = 'https://api.test.sabre.com/v1/';

    const CLIENT_KEY = 'V1:p948fbdn4f4rsi8r:DEVCENTER:EXT';

    const CLIENT_SECRET = 'lPAf6iC3';

    const CACHE_KEY = "sabreOath2";

    private $container;
    private $cache;

    public function __construct(FilesystemCache $cache)
    {
        //$this->container = $container;
        //$this->cache = $this->container->get('cache');
        $this->cache = $cache;

    }

    private function getAccessToken()
    {
        $encKey = base64_encode(self::CLIENT_KEY);
        $encSecret = base64_encode(self::CLIENT_SECRET);

        return base64_encode($encKey . ':' . $encSecret);
    }

    function getApiKey()
    {

        $oath2 = $this->cache->fetch(self::CACHE_KEY);
        if ($oath2) {
            return $oath2;
            //$cache->save("some key", $id);
        }

        $response = $this->callAuth();

        $authObj = json_decode($response);

        $return = null;

        if(isset($authObj->access_token)){
            $this->cache->save(self::CACHE_KEY, $authObj->access_token, 899 );
            $return =$authObj->access_token;
        }

        return $return;
    }

    private function callAuth()
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, self::BASE_URI . 'auth/token');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           count('grant_type=client_credentials') );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     "grant_type=client_credentials" );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic '. $this->getAccessToken()
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}
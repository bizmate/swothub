<?php
namespace AppBundle\Model;

use GuzzleHttp\Client as GuzzleClient;



class Competitors {

    const IDOL_KEY = '6997fba7-6014-4fde-bbec-dd85c1d9fee9';
    const IDOL_BASE = 'https://api.idolondemand.com';


    public function getByUrl ($url)
    {
        //$this->httpClient->
    }

    public function getPubResources()
    {
        //https://api.idolondemand.com/1/api/sync/listresources/v1?apikey=6997fba7-6014-4fde-bbec-dd85c1d9fee9

        $response = $this->getClient()->get(
            '/1/api/sync/listresources/v1?apikey=' . self::IDOL_KEY
        ); //->send(); var_dump($response);die;//->send();
        return json_decode($response->getBody() ) ;

    }

    private function getClient()
    {
        return new GuzzleClient([
            'base_uri' => self::IDOL_BASE
        ]);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/06/15
 * Time: 04:36
 */

namespace AppBundle\Model;
use GuzzleHttp\Client as GuzzleClient;



class SabreHotels {

    public function __construct()
    {}

    public function getHotels($dest, $from, $to){
        $url = "http://dev.jellyfishsurpriseparty.com/polygon/rates/$dest/$from/$to";

        $response = $this->getClient()->get($url);

        $hotels = json_decode($response->getBody());

        return $hotels;
    }

    private function getClient()
    {
        return new GuzzleClient();
    }

}
<?php
namespace AppBundle\Model;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Stream;

class Sabre {

    const BASE_URI = 'https://api.test.sabre.com/v1/';

    private $sabreAuth2;

    public function __construct(SabreOath2 $sabreAuth2)
    {
        $this->sabreAuth2 = $sabreAuth2;
    }

    public function getItinerary($to, $from, $startdate, $enddate)
    {
        $action = 'shop/flights';

        $urlParams =[
            'origin' => $from,
            'destination' => $to,
            'departuredate' => $startdate,
            'returndate' => $enddate
        ];

        $extraVars =
            'onlineitinerariesonly=N&limit=10&offset=1&eticketsonly=N&sortby=totalfare&order=asc&sortby2=departuretime&order2=asc&pointofsalecountry=GB';

        $callUrl = self::BASE_URI . $action . '?' . http_build_query($urlParams) . '&' . $extraVars;
        //echo $callUrl;die;


        $response = $this->getClient()->get(
            $callUrl,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->sabreAuth2->getApiKey(),
                    'Accept'     => 'application/json',
                ]
            ]
        );

        //var_dump($response->getBody()->getContents());die;

        if($response->getStatusCode() == 200){
            var_dump($response->json() ); die;
            $body = $response->getBody();


            if($body instanceof Stream)
            {
                $contents = $body->getContents();

                //echo $contents; die;
                return json_decode( trim($contents) );
                //return $contents ;
            }
            return json_decode($body);
        }

        return null;

    }

    private function getClient()
    {
        return new GuzzleClient([
            //'base_uri' => self::BASE_URI
        ]);
    }

}
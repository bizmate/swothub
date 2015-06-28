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
        //return null;

        $action = 'shop/flights';

        $urlParams =[
            'origin' => $from,
            'destination' => $to,
            'departuredate' => $startdate,
            'returndate' => $enddate
        ];

        $extraVars =
            'onlineitinerariesonly=N&limit=5&offset=1&eticketsonly=N&sortby=totalfare&order=asc&sortby2=departuretime&order2=asc&pointofsalecountry=GB';

        $callUrl = self::BASE_URI . $action . '?' . http_build_query($urlParams) . '&' . $extraVars;

        $response = $this->curlGet($callUrl);
        //$response = $this->getThroughGuzzle($callUrl);


        if($response = json_decode($response)){

            $itn = [];
            foreach($response->PricedItineraries as $itinerary){

                $fragments = [];

                foreach(
                    $itinerary->AirItinerary->OriginDestinationOptions->OriginDestinationOption as $frag
                )
                {
                    //echo json_encode($frag->FlightSegment);die;
                    //echo count($frag->FlightSegment);die;

                    foreach($frag->FlightSegment as $subFrag){
                        //echo json_encode($subFrag);die;
                        $fragments[] = [
                            'departAirport' => $subFrag->DepartureAirport->LocationCode,
                            'arrAirport' => $subFrag->ArrivalAirport->LocationCode,
                            'airline' => $subFrag->MarketingAirline->Code,
                            'flighN' => $subFrag->FlightNumber,
                            'departTime' => $subFrag->DepartureDateTime,
                            'arrTime' => $subFrag->ArrivalDateTime,
                        ];
                    }

                }
                //echo json_encode($itinerary->AirItineraryPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerFare->TotalFare);die;

                $itn[] = [
                    'total' => $itinerary->AirItineraryPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerFare->TotalFare->Amount ,
                    'curr' => $itinerary->AirItineraryPricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerFare->TotalFare->CurrencyCode ,
                    'type' => $itinerary->AirItinerary->DirectionInd ,
                    'fragments' => $fragments
                ];
            }
            return $itn;
        }

        return null;
    }

    private function getThroughGuzzle($callUrl)
    {
        $response = $this->getClient()->get(
            $callUrl,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->sabreAuth2->getApiKey(),
                    'Accept'     => 'application/json',
                ]
            ]
        );

        if($response->getStatusCode() == 200){
            $body = $response->getBody();

            if($body instanceof Stream)
            {
                $contents = $body->getContents();

                return json_decode( trim($contents) );
            }
            return json_decode($body);
        }
    }

    private function curlGet( $url)
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,  $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
            'Authorization: Bearer '. $this->sabreAuth2->getApiKey(),
            'Accept'     => 'application/json'
        ));

        $response = curl_exec($ch);

        if(!$response){
            echo 'Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch);die;
        }

        curl_close($ch);

        return $response;
    }

    private function getClient()
    {
        return new GuzzleClient([
            //'base_uri' => self::BASE_URI
        ]);
    }

}
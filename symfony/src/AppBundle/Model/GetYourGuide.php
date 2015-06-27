<?php
namespace DestinationHack\GetYourGuide;

class GetYourGuide
{
    const GYG_API_URL = "https://api-hackathon.getyourguide.com/1";
    const API_KEY = "vdnzrzYDC0gHpt4zCQmgvD5I1xpNFK2Vw2EVpP12u2d9D16U";

    public function getTours($options = array())
    {
        $url = $this->buildApiUri('tours', $this->buildTourParams($options));
        return $this->query($url);
    }

    /**
    * Private Methods
    **/

    private function buildTourParams($params)
    {
        // ['location'=>'Berlin'] will become ['location'=>'17']
        if (array_key_exists('location', $params)) {
            $params['location'] = $this->getLocationId($params['location']);
        }

        return $params;
    }

    private function buildLocationParams($cityName)
    {
        return array('q' => $cityName, 'location_type' => 'city' );
    }

    private function getLocationId($city)
    {
        $options = $this->buildLocationParams($city);

        $url = $this->buildApiUri('locations', $options);
        return json_decode($this->query($url))->data->locations[0]->location_id;
    }

    private function buildApiUri($endpoint, $urlParams)
    {
        return self::GYG_API_URL .'/'. $endpoint . '?' . http_build_query(array_merge($urlParams, $this->default_params()));
    }

    private function default_params()
    {
        return array(
            'cnt_language' => 'en',
            'currency' => 'EUR'
        );
    }

    private function query($url)
    {
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Access-Token: vdnzrzYDC0gHpt4zCQmgvD5I1xpNFK2Vw2EVpP12u2d9D16U',
            'Accept: application/json'
        ));
        $results = curl_exec($ch);
        curl_close($ch);

        if (empty($results)) {
          return json_encode(["error" => "something went wrong"]);
        }

        return $results;
    }
}

?>

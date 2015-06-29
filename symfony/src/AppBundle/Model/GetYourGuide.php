<?php
namespace AppBundle\Model;

class GetYourGuide
{
    const GYG_API_URL = "https://api-hackathon.getyourguide.com/1";
    const API_KEY = "vdnzrzYDC0gHpt4zCQmgvD5I1xpNFK2Vw2EVpP12u2d9D16U";

    public $tourIds = array();
    public $tourData = array();

    private $default_params = array(
            'cnt_language' => 'en',
            'currency' => 'GBP'
        );

    /**
    * Public Methods
    **/

    public function tours($options = array())
    {
        $url = $this->buildApiUri('tours', $this->resolveLocationId($options));
        $tours = json_decode($this->query($url))->data->tours;

        $this->storeTourIds($tours);
        $this->storeTourData($tours);

        return $this->tourData;
    }

    public function tour($tour_id)
    {
        $url = $this->buildApiUri('tours/'. $tour_id);

        $tours = json_decode($this->query($url))->data->tours;
        $this->storeTourData($tours);

        return $this->tourData;
    }

    /**
    * Private Methods
    **/

    private function storeTourIds($tours)
    {
        $this->tourIds = array_map(
            function($tour){
                return $tour->tour_id;
            }, $tours);
    }
    private function storeTourData($tours)
    {
        $this->tourData = array_map(
            function($tour){
                $picUrl = $tour->pictures[0]->url;
                $price = $tour->price->values->amount;
                $duration = $tour->durations[0]->duration;
                $unit = $tour->durations[0]->unit;

                return [
                    "tour_id" => $tour->tour_id,
                    "title" => $tour->title,
                    "description" => $tour->abstract,
                    "pictures" => [
                        "420x320" => $this->pictureUrl($picUrl, 127),
                        "720x480" => $this->pictureUrl($picUrl, 128),
                        "1200x800" => $this->pictureUrl($picUrl, 129),
                        "1920x1280" => $this->pictureUrl($picUrl, 130)
                    ],
                    "price" => $price,
                    "duration" => [
                        "duration" => $duration,
                        "unit" => $unit
                    ]
                ];
            }, $tours);
    }

    private function pictureUrl($url, $sizeCode)
    {
        return str_replace("[format_id]",$sizeCode, $url);
    }

    private function resolveLocationId($params)
    {
        // ['location'=>'Berlin'] will become ['location'=>'17']
        if (array_key_exists('location', $params)) {
            $params['location'] = $this->findLocationId($params['location']);
        }

        return $params;
    }

    private function buildLocationParams($cityName)
    {
        return array('q' => $cityName, 'location_type' => 'city' );
    }

    private function findLocationId($city)
    {
        $options = $this->buildLocationParams($city);

        $url = $this->buildApiUri('locations', $options);
        return @json_decode($this->query($url))->data->locations[0]->location_id;
    }

    private function buildApiUri($endpoint, $urlParams = array())
    {
        return self::GYG_API_URL .'/'. $endpoint . '?' . http_build_query(array_merge($this->default_params, $urlParams));
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
          return ["error" => "something went wrong"];
        }

        return $results;
    }
}

?>

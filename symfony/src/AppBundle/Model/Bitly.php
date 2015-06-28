<?php
namespace AppBundle\Model;

class Bitly
{
    const BITLY_URL = "https://api-ssl.bitly.com/";
    const BITLY_USR = "gebbione";
    const API_KEY = "R_211815952ab34127be459439b7baa382";


    /* public function getShorter($url)
    {
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, BITLY_URL);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST,           count('grant_type=client_credentials') );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     "grant_type=client_credentials" );

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64encode(BITLY_USR + ":" + API_KEY),
            'Accept: application/json'
        ));
        $results = curl_exec($ch);
        curl_close($ch);
    }*/

    public function getShortUrl($url)
    {
        //function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
        //{
            //create the URL
            $format='xml';
            $bitly = 'http://api.bit.ly/shorten?version=2.0.1&longUrl='.urlencode($url).'&login='. self::BITLY_USR .'&apiKey='. self::API_KEY .'&format=xml';

            //get the url
            //could also use cURL here
            $response = file_get_contents($bitly);

            //parse depending on desired format
            if(strtolower($format) == 'json')
            {
                $json = @json_decode($response,true);
                return $json['results'][$url]['shortUrl'];
            }
            else //xml
            {
                $xml = simplexml_load_string($response);
                return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
            }
        //}
    }
}

?>

<?php
namespace AppBundle\Model;

class Giata
{
    const GIATA_MULTI_URL = "http://multicodes.giatamedia.com/webservice/rest/1.0/properties/gds/sabre_tn/";
    const GIATA_MULTI_USR = "multicodes|hackathon.com"; //codes
    const GIATA_MULTI_PASS = "xYpzHfjJ";
    const GIATA_MULTI_AUTH = 'Basic bXVsdGljb2Rlc3xoYWNrYXRob24uY29tOnhZcHpIZmpK';
    const GIATA_LIN_USR = "ghgml|hackathon.com"; //contents
    const GIATA_LIN_PASS = "b3sW7nob";
    const GIATA_LIN_URL = "http://ghgml.giatamedia.com/webservice/rest/1.0/items/";
    const GIATA_LIN_AUTH = 'Basic Z2hnbWx8aGFja2F0aG9uLmNvbTpiM3NXN25vYg==';


    public function getImg($sabreChain, $sabreCode)
    {

        $giataCode = $this->getGiataCode($sabreChain, $sabreCode);//return $giataCode;

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,  self::GIATA_LIN_URL . $giataCode);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
            'Authorization: '. self::GIATA_LIN_AUTH,
            'Accept'     => 'application/xml'
        ));

        $response = curl_exec($ch);

        $xml = $this->xmlToSimple($response);

        curl_close($ch);

        foreach ($xml->children() as $item) {
            foreach($item->children() as $props){
                var_dump( $item->xpath('//images')[0]);
            }

            //$props =
            $res = $props['images'];
        }

        return $res;
    }

    private function getGiataCode($sabreChain, $sabreCode)
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,  self::GIATA_MULTI_URL . $sabreChain . '/' . $sabreCode);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
            'Authorization: '. self::GIATA_MULTI_AUTH,
            'Accept'     => 'application/xml'
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $xml = $this->xmlToSimple($response);

        foreach ($xml->children() as $property) {
            return (string) $property->attributes()['giataId'][0];
        };


    }

    private function xmlToSimple($xml){
        return simplexml_load_string($xml);
    }
}

?>

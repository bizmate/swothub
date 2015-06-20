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

    public function getNews($text)
    {
        $resUrl = '/1/api/sync/querytextindex/v1?absolute_max_results=20&indexes=news_eng&print=all&summary=quick&text=';

        $response = $this->getClient()->get(
            $resUrl . urlencode($text) . '&apikey=' . self::IDOL_KEY
        );

        $newsResp = json_decode($response->getBody() );

        //echo json_encode($newsResp->documents);die;

        if(!isset($newsResp->documents)){
            return false;
        }


        $newsItems = [];

        foreach($newsResp->documents as $doc)
        {
            $sentiment = $this->getSentiment($doc->summary);

            $tmp['title'] = $doc->title;
            $tmp['summary'] = $doc->summary;
            $tmp['score'] = $sentiment->score;
            $tmp['sentiment'] = $sentiment->sentiment;
            $newsItems[]  = $tmp;
        }

        return  $newsItems;
    }

    private function getClient()
    {
        return new GuzzleClient([
            'base_uri' => self::IDOL_BASE
        ]);
    }

    private function getSentiment($fullText)
    {
        // /1/api/sync/analyzesentiment/v1?text=hello+world&language=eng&apikey=6997fba7-6014-4fde-bbec-dd85c1d9fee9”
        $resUrl = '/1/api/sync/analyzesentiment/v1?language=eng&text='  ;

        $response = $this->getClient()->get(
            $resUrl . urlencode($fullText) . '&apikey=' . self::IDOL_KEY
        );

        $sentiment = json_decode($response->getBody() );

        if(!$sentiment->aggregate){
            return false;
        }
        return $sentiment->aggregate;
    }

}
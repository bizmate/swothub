<?php
namespace AppBundle\Model;

use GuzzleHttp\Client as GuzzleClient;

class News {

    const IDOL_KEY = '6997fba7-6014-4fde-bbec-dd85c1d9fee9';
    const IDOL_BASE = 'https://api.idolondemand.com';

    public function getNews($text)
    {
        $response = $this->getClient()->get(
            $this->getQueryTextIndexUrl([
                'absolute_max_results' => 20,
                'indexes' => 'news_eng',
                'print' => 'all',
                'summary' => 'quick',
                'text' => urlencode($text),
                'apikey' => self::IDOL_KEY
            ])
        );

        $newsResp = json_decode($response->getBody());

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
        $resUrl = '/1/api/sync/analyzesentiment/v1?language=eng&text='  ;

        $response = $this->getClient()->get(
            $this->getSentimentUrl([
                'language' =>'eng',
                'text' => urlencode($fullText),
                'apikey' => self::IDOL_KEY
            ])
        );

        $sentiment = json_decode($response->getBody() );

        if(!$sentiment->aggregate){
            return false;
        }
        return $sentiment->aggregate;
    }

    private function getQueryTextIndexUrl($urlParams = [])
    {
        return $this->buildApiUrl('querytextindex', $urlParams);
    }

    private function getSentimentUrl($urlParams = [])
    {
        return $this->buildApiUrl('analyzesentiment', $urlParams);
    }

    private function buildApiUrl($action, $urlParams = [])
    {
        return '/1/api/sync/'.$action.'/v1?' . http_build_query($urlParams);
    }
}

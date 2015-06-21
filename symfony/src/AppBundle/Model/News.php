<?php
namespace AppBundle\Model;

use GuzzleHttp\Client as GuzzleClient;

class News {

    const IDOL_KEY = '6997fba7-6014-4fde-bbec-dd85c1d9fee9';
    const IDOL_BASE = 'https://api.idolondemand.com';

    public function getNews($text, $total = 10)
    {
        $response = $this->getClient()->get(
            $this->getQueryTextIndexUrl([
                'absolute_max_results' => $total,
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
        $newsFullText = '';

        foreach($newsResp->documents as $doc)
        {
            //echo json_encode($doc);die;
            $sentiment = $this->getSentiment($doc->summary);

            $tmp['title'] = $doc->title;
            $tmp['summary'] = $doc->summary;
            $tmp['url'] = $doc->reference;
            $tmp['date'] = isset($doc->date)? $doc->date : null;
            $tmp['score'] = $sentiment->score;
            $tmp['sentiment'] = $sentiment->sentiment;
            $newsItems[]  = $tmp;
            $newsFullText = $newsFullText . $tmp['summary'];
        }

        //echo $newsFullText;die;

        $companies = null;

        if(strlen($newsFullText) > 0){
            $companies = $this->getCompanies($newsFullText);
        }

        $news = [];
        $news['results'] = $newsItems;
        $news['companies'] = isset($companies)? $companies : null ;

        return  $news;
        //return  $newsItems;
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
                'text' => $fullText,
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

    private function getCompaniesUrl($urlParams = [])
    {
        return $this->buildApiUrl('extractentity', $urlParams);
    }

    private function buildApiUrl($action, $urlParams = [])
    {
        return '/1/api/sync/'.$action.'/v1?' . http_build_query($urlParams);
    }

    private function getCompanies($fullText){
        // /1/api/sync/extractentity/v1?text=Who is better? Lebron James or Kobe Bryant?&entity_type=companies_eng
        //$client->post('http://httpbin.org/post', ['body' => $body]);

        $searchPar = ['entity_type' =>'companies_eng', 'text' => $fullText ];

        /* var_dump($this->getCompaniesUrl([
            //'entity_type' =>'companies_eng',
            //'text' => $fullText,
            'apikey' => self::IDOL_KEY
        ]));die; */

        $response = $this->getClient()->get(
            $this->getCompaniesUrl([
                'entity_type' =>'companies_eng',
                'text' => $fullText,
                'unique_entities' => 'true',
                'apikey' => self::IDOL_KEY
            ])
        );

        $companies = json_decode($response->getBody() );

        if(!isset($companies->entities)){
            return false;
        }

        $companiesRes = [];

        foreach($companies->entities as $company){
            $companiesRes[] = $company->normalized_text;
        }

        return $companiesRes;

    }
}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Model\News;
use AppBundle\Model\ItineraryResponse;
use AppBundle\Model\SabreHotels;
use AppBundle\Model\TwilioAdapter;
use AppBundle\Model\Bitly;


class ApiController extends FOSRestController
{
    /**
     * @param $url
     * @return array
     */
     //@desc - path resource on  get_competitors  GET   /api/competitors/{url}.{_format}


    public function getCompetitorsAction($url)
    {
        $items = array('bla1', 'bla2', $url);

        if(count($items) == 0)
            throw $this->createNotFoundException('No items found');


        return $items;
    }

    public function getNewsAction($text)
    {
        $news = new News();
        return $news->getNews($text);
    }

    public function getNewsTotalAction($text, $total)
    {
        $news = new News();
        return $news->getNews($text, $total);
    }

    public function getToFromStartdateEnddateAction($to, $from, $startdate, $enddate)
    {
        $sabreClient = $this->container->get('sabre');
        $gyg = $this->container->get('getyourguide');

        $itineraries = $sabreClient->getItinerary($to, $from, $startdate, $enddate);

        $hotelsClient = new SabreHotels();
        $hotels = $hotelsClient->getHotels($to, $startdate, $enddate);

        $destination = $sabreClient->resolveCityName($to);
        $activities = $gyg->tours(['location' => $destination]);

        return new ItineraryResponse($itineraries, $hotels, $activities);
    }

    public function getCitytoursAction($cityname)
    {
        $gyg = $this->container->get('getyourguide');

        return $gyg->tours(['location' => $cityname]);
    }

    public function getTourAction($tour_id)
    {
        $gyg = $this->container->get('getyourguide');
        return $gyg->tour($tour_id);
    }

    public function getCitynameAction($airportCode)
    {
        $sabreClient = $this->container->get('sabre');
        return $sabreClient->resolveCityName($airportCode);
    }

    public function postSmsBudgetStartEndDestinationAction($phoneNumber, $budget, $start, $end, $destination)
    {
        $mobilePattern = '447\d\d\d\d\d\d\d\d';

        //maverick.html?budget=500&start=2015-06-28&end=2015-07-10&destination=FCO

        $bitlyClient = new Bitly();

        $longUrl = 'http://destination.bizmate.space/maverick.html?budget=' . $budget
            .'&start=' . $start . '&end=' . $end . '&destination='. $destination;

        $shortUrl = $bitlyClient->getShortUrl($longUrl);

        $result = null;

        //echo $phoneNumber . ' -> ' . preg_match( $mobilePattern , '+'  .$phoneNumber); die;

        //if(preg_match( $mobilePattern , '+'  .$phoneNumber) )
        if(true )
        {
            $twilioClient = new TwilioAdapter();
            $result = $twilioClient->sendMsg('+'  .$phoneNumber , $shortUrl) ;
        }

        return array(
            'result' => $result
        );
    }
}

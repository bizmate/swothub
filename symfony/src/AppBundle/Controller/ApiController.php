<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Model\News;


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

    public function getLocationStartdateEnddateAction($location, $startdate, $enddate)
    {
        return array('location' => $location, 'startdate'=>$startdate, 'enddate'=>$enddate);
    }


}

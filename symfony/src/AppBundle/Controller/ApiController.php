<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Model\Competitors;


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
        $competitors = new Competitors();

        $news = $competitors->getNews($text);

        return $news;
    }


}

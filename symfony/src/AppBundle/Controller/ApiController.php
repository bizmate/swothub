<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\Controller\FOSRestController;

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

        //var_dump($items);die;

        //var_dump($this->view($items, 200));
        if(count($items) == 0)
            throw $this->createNotFoundException('No items found');

        /* $view = $this->view($items, 200)
            ->setTemplate("BizmateWiisBundle:Api:getItems.html.twig")
            ->setTemplateVar('items')
        ;
        //var_dump($view);die;

        $return = $this->handleView($view);
        //var_dump($return);
        return $return; */

        return $items;
    }
}

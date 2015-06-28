<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Model\Competitors;
use AppBundle\Model\SabreHotels;
use AppBundle\Model\SabreOath2;
use AppBundle\Model\TwilioAdapter;




class DevController extends Controller
{
    /**
     * @Route("idolresources")
     * @Template()
     */
    public function getIdolResourcesAction()
    {
        $competitors = new Competitors();

        $pubRes = $competitors->getPubResources();

        return array(
                'pubRes' => $pubRes
        );
    }

    /*
     * //@Route("/news/{text}")
     * @Template()

    public function getIdolNewsAction($text)
    {
        $competitors = new Competitors();

        $news = $competitors->getNews($text);

        return array(
            'news' => $news
        );
    }*/

    /*
     * @Route("/insertcp")
     * @Template()

    public function insertCpAction()
    {
        $csp = new CpsAdapter();

        $result = $csp->insertSearch('id', array('something'=>'withsomething'));

        return array(
            'result' => $result
        );
    }*/

    /**
     * @Route("/oathsabretest")
     * @Template()
     */
    public function oathsabreAction()
    {
        $client = new SabreOath2($this->container->get('cache'));

        $result = $client->getApiKey();

        return array(
            'result' => $result
        );
    }

    /**
     * @Route("/oathsabretest2")
     * @Template()
     */
    public function oathsabre2Action()
    {
        $client = $this->container->get('sabreauth');

        $result = $client->getApiKey();

        return array(
            'result' => $result
        );
    }


    /**
     * @Route("/hotelstest")
     * @Template()
     */
    public function hotelstestAction()
    {
        $hotelClient = new SabreHotels();
        $result = $hotelClient->getHotels('FCO', '2015-07-10','2015-07-12')  ;
        return array(
            'result' => $result
        );
    }

    /**
     * @Route("/twiliotest")
     * @Template()
     */
    public function twiliotestAction()
    {
        $twilioClient = new TwilioAdapter();
        $result = $twilioClient->sendMsg('+4475982491') ;
        return array(
            'result' => $result
        );
    }

}

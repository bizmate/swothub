<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Model\Competitors;
//use AppBundle\Model\CpsAdapter;
use AppBundle\Model\SabreOath2;



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

}

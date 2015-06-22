<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Model\Competitors;
use AppBundle\Model\CpsAdapter;



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

    /**
     * @Route("/news/{text}")
     * @Template()
     */
    public function getIdolNewsAction($text)
    {
        $competitors = new Competitors();

        $news = $competitors->getNews($text);

        return array(
            'news' => $news
        );
    }

    /**
     * @Route("/insertcp")
     * @Template()
     */
    public function insertCpAction()
    {
        $csp = new CpsAdapter();

        $result = $csp->insertSearch('id', array('something'=>'withsomething'));

        return array(
            'result' => $result
        );
    }

}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Subscriber;
use AppBundle\Type\Subscriber as SubscriberType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/subscribe", name="subscribe")
     */
    public function subscribeAction(Request $request)
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);

        $form->handleRequest($request);

        echo 'is form sub ' . $form->isSubmitted() .  ' and is valid ' . $form->isValid() . '<-';
        var_dump($subscriber);
        //die;
        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($form);
            die;
        }

        return $this->render('AppBundle:Subscribe:subscribeForm.html.twig',
            array(
                'subscribeForm'=>$form->createView()
            )
        );
    }
}

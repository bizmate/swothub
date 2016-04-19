<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 13/02/16
 * Time: 11:55
 */

namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class Subscriber extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fname', Type\TextType::class, array('label' => 'First Name'));
        $builder->add('lname', Type\TextType::class, array('label' => 'Last Name'));
        $builder->add('email', Type\EmailType::class,  array('label' => 'Email *'));

        $builder->add('Join','submit');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Subscriber'
        ));
    }

    public function getName()
    {
        return 'subscriber';
    }
}
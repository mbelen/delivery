<?php

namespace Backend\CustomerAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Backend\AdminBundle\Form\EventListener\CategoriaSubscriber;
use Backend\AdminBundle\Form\EventListener\SubcategoriaSubscriber;


class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $customerId = $options['customerId'];

        $builder
            ->add('name')
            ->add('desde', 'datetime', array(
                'input' => 'datetime',
                'date_widget' => 'choice',
                'time_widget' => 'choice',
                'required' => true,

            ))
            ->add('hasta', 'datetime', array(
                'input' => 'datetime',
                'date_widget' => 'choice',
                'time_widget' => 'choice',
                'required' => true,

            ))

            ->add('file', 'file', array("required" => false))
            ->add('sucursal','entity',array(
                'class'=>'BackendCustomerAdminBundle:Sucursal',
                'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                    return $er->createQueryBuilder('u')
                        ->where('u.customer = '.$customerId)
                        ->orderBy('u.name', 'ASC');
                },

                'multiple'=>false,
                'mapped'=>true,
                'required'=>true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Backend\CustomerAdminBundle\Entity\Banner',
            'customerId' => null

        ));

    }

    public function getName()
    {
        return 'backend_customeradminbundle_bannertype';
    }
}

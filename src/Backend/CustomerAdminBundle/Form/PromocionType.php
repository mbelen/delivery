<?php

namespace Backend\CustomerAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Backend\AdminBundle\Form\EventListener\CategoriaSubscriber;
use Backend\AdminBundle\Form\EventListener\SubcategoriaSubscriber;


class PromocionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $customerId = $options['customerId'];

        $builder
            ->add('name')
            ->add('detail')
            ->add('terms')
            ->add('stock')
            ->add('stop','checkbox',array("required" => false))
            ->add('desde', 'datetime', array(
                'input' => 'datetime',
                'date_widget' => 'choice',
                'date_format' => 'dd-MM-yyyy',
               // 'time_widget' => 'choice',
                'years' => range(Date('Y'), 2020),
                'required' => true,

            ))
            ->add('hasta', 'datetime', array(
                'input' => 'datetime',
                'date_widget' => 'choice',
                'date_format' => 'dd-MM-yyyy',
              //  'time_widget' => 'choice',
                'years' => range(Date('Y'), 2020),
                'required' => true,

            ))
            ->add('file', 'file', array("required" => false))
            //deben ser las sucursales del customer
            ->add('type', 'choice', array(
                'choices' => array( 1 => 'Porcentaje', 2 => 'Unidades'),
            ))
            ->add('status', 'choice', array(
                'choices' => array( 1 => 'Vigente', 2 => 'Pausada'),
                'required' => true,
                'mapped' => true
            ))
            ->add('productos','entity',array(
                'class'=>'BackendCustomerAdminBundle:Producto',
                'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                    return $er->createQueryBuilder('u')
                  ->innerjoin("u.sucursales", "s")
                  ->where('s.customer = '.$customerId)
                  ->orderBy('u.name', 'ASC');
                },

                'multiple'=>true,
                'mapped'=>true,
                'required'=>false,
                'empty_data'  => null,
            ))
            ->add('productosExcluidos','entity',array(
                'class'=>'BackendCustomerAdminBundle:Producto',
                'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                    return $er->createQueryBuilder('u')
                  ->innerjoin("u.sucursales", "s")
                  ->where('s.customer = '.$customerId)
                  ->orderBy('u.name', 'ASC');
                },

                'multiple'=>true,
                'mapped'=>true,
                'required'=>false,
                'empty_data'  => null,
            ))
            ->add('sucursales','entity',array(
                'class'=>'BackendCustomerAdminBundle:Sucursal',
                'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                    return $er->createQueryBuilder('u')
                        ->where('u.customer = '.$customerId)
                        ->orderBy('u.name', 'ASC');
                },

                'multiple'=>true,
                'mapped'=>true,
                'required'=>true
            ))

            ->add('mediosPago','entity',array(
                'class'=>'BackendAdminBundle:PayMethod',
                'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                    return $er->createQueryBuilder('u')
                        //->where('u.customer = '.$customerId)
                        ->orderBy('u.name', 'ASC');
                },
                'multiple'=>true,
                'mapped'=>true,
                'required'=>false
            ))

            ->add('subcategorias','entity',array(
            'class'=>'BackendAdminBundle:Subcategoria',
            'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                return $er->createQueryBuilder('u')
                    //->where('u.customer = '.$customerId)
                    ->orderBy('u.name', 'ASC');
            },
            'multiple'=>true,
            'mapped'=>true,
            'required'=>false
            ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Backend\CustomerAdminBundle\Entity\Promocion',
            'customerId' => null

        ));

    }

    public function getName()
    {
        return 'backend_customeradminbundle_promociontype';
    }
}

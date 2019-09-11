<?php

namespace Backend\CustomerAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Backend\AdminBundle\Form\EventListener\CategoriaSubscriber;
use Backend\AdminBundle\Form\EventListener\SubcategoriaSubscriber;
use Doctrine\ORM\EntityRepository;

class PedidoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentarios')
			->add('sucursal', 'entity',array(
            'class'=>'BackendCustomerAdminBundle:Sucursal',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder("u")
                         ->select("u")
                         ->where("u.is_active = true")
                         ->orderBy('u.name', 'ASC');
                      
            },'mapped'=>true,'required'=>true,'multiple'=>false))


            /*
			->add('sucursales', 'collection',
		            array(
		                'type' => new Backend\CustomerAdminBundle\Entity\Sucursal(),
		                'allow_add' => true,
		                'allow_delete' => true,
		                'prototype' => true,
		                'property_path' => false
		            )
		        )
			*/

            ;

            
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Backend\CustomerAdminBundle\Entity\Pedido'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'backend_adminbundle_producto';
    }
}

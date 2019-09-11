<?php

namespace Backend\CustomerAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Backend\AdminBundle\Form\EventListener\CategoriaSubscriber;
use Backend\AdminBundle\Form\EventListener\SubcategoriaSubscriber;
use Doctrine\ORM\EntityRepository;

class ProductoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {             
        $customerId = $options['customerId'];
        
        
        $builder
            ->add('name')
            ->add('code')
            ->add('file', 'file', array("required" => false))
			      //deben ser las sucursales del customer
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
            
			      
            
			->add('precio')
            ->add('alwaysAvailable')
            ->add('maxVariedad')
            ->add('minVariedad')
            ->add('qtyVariedad','checkbox',array(
                'value'=>1,
                'required'=>false
            ))
            ->add('isActive','checkbox',array(
             'value'=>1,
             'required'=>false
            ))	
            ->add('file', 'file', array("required" => false))

            ->add('description')
            ;
           
           
           //deben ser las variedades del producto que ya esten cargadas previamente para el mismo customer
           			
          
               $builder->add('variedades','entity',array(
              'class'=>'BackendCustomerAdminBundle:Variedad',
              'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                  return $er->createQueryBuilder('u')
                  ->where('u.customer = '.$customerId)
                  ->orderBy('u.name', 'ASC');
            },
    
              'multiple'=>true,
              'mapped'=>true,
              'required'=>true
               ));
           
            
          
           $categoriaSubscriber = new CategoriaSubscriber($builder->getFormFactory());
			     $builder->addEventSubscriber($categoriaSubscriber); 
           
           $subcategoriaSubscriber = new SubcategoriaSubscriber($builder->getFormFactory());
			     $builder->addEventSubscriber($subcategoriaSubscriber);   
            
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Backend\CustomerAdminBundle\Entity\Producto',
            'customerId' => null
            
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'backend_customeradminbundle_producto';
    }
}

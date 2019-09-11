<?php

namespace Backend\CustomerAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class VariedadType extends AbstractType
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
	        ->add('active')
	        ->add('description')
            ->add('productos','entity',array(
            'class'=>'BackendCustomerAdminBundle:Producto',
            'query_builder'=>function(EntityRepository $er ) use ( $customerId ) {
                  return $er->createQueryBuilder('u')
                  ->innerjoin("u.sucursales", "s")
                  ->where('s.customer = '.$customerId)
                  ->orderBy('u.name', 'ASC');
            },'mapped'=>true,'required'=>true,'multiple'=>true))	
			;
         
                    
            
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Backend\CustomerAdminBundle\Entity\Variedad',
            'customerId' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'backend_customeradminbundle_variedad';
    }
}

<?php

namespace Backend\CustomerAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Backend\AdminBundle\Form\EventListener\ZonaSubscriber;
use Backend\AdminBundle\Form\EventListener\BarrioSubscriber;
class DireccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            
            $builder->add('calle', 'text');
            $builder->add('numero','text');
            $builder->add('piso');
            $builder->add('depto');
            $builder->add('lat');
            $builder->add('lon');
            $builder->add('zip','text',array('required'=>true));
            
            $builder->add('tipo','entity',array(
                'class'=>'BackendCustomerAdminBundle:TipoDireccion',
                'property'=>'name',
                'multiple'=>false
            ));
            $builder->add('isDefault','checkbox',array(
             'value'=>1,
             'label'=>"Direccion por defecto",
             'required'=>false
            ));
            
            $zonaSubscriber = new ZonaSubscriber($builder->getFormFactory());
			     $builder->addEventSubscriber($zonaSubscriber); 
           
           $barrioSubscriber = new BarrioSubscriber($builder->getFormFactory());
			     $builder->addEventSubscriber($barrioSubscriber); 
				
		  
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Backend\CustomerAdminBundle\Entity\Direccion'
        ));
    }

    public function getName()
    {
        return 'backend_customeradminbundle_direccion';
    }
}

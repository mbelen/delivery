<?php
 
namespace Backend\AdminBundle\Form\EventListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

use Backend\AdminBundle\Entity\Categoria;
 
class SubcategoriaSubscriber implements EventSubscriberInterface
{
    private $factory;
 
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }
 
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_BIND     => 'preBind'
        );
    }
 
    private function addSubcategoriaForm($form, $categoria)
    {
        $form->add($this->factory->createNamed('subcategoria','entity', null, array(
            'class'         => 'BackendAdminBundle:Subcategoria',
            'empty_value'   => 'Seleccione Subcategoria',
            'auto_initialize' => false,
            "property"=>"name",
            "required"=>false,
            'query_builder' => function (EntityRepository $repository) use ($categoria) {
                $qb = $repository->createQueryBuilder('subcategoria')
                       ->innerJoin('subcategoria.categoria', 'categoria');
                       
                if ($categoria instanceof Categoria) {
                    $qb->where('subcategoria.categoria = :categoria_id')
                    ->setParameter('categoria_id', $categoria);
                } elseif (is_numeric($categoria)) {
                    $qb->where('categoria.id = :categoria_id')
                    ->setParameter('categoria_id', $categoria);
                } else {
                    $qb->where('categoria.name = :categoria_id')
                    ->setParameter('categoria_id', null);
                }  
                     
                $qb->orderBy('subcategoria.name');
                return $qb;
            }
        )));
    }
 
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
        
        
        $categoria = ($data->getSubcategoria()) ? $data->getSubcategoria()->getCategoria() : null ;
        $this->addSubcategoriaForm($form, $categoria);
    }
 
    public function preBind(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
       
        $categoria = array_key_exists('categoria', $data) ? $data['categoria'] : null;
        $this->addSubcategoriaForm($form, $categoria);
    }
}
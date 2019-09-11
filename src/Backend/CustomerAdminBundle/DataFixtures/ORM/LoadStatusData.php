<?php 
namespace Backend\CustomerAdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\CustomerAdminBundle\Entity\Status;

class LoadStatusData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

/**
     * @var ContainerInterface
     */
    private $container;

 public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tipo1 = new Status();
        $tipo1->setName("Pendiente");
        $tipo1->setOrden(1);
        $manager->persist($tipo1);
        $manager->flush();
        $this->addReference('estado-pendiente', $tipo1);
        
        $tipo2 = new Status();
        $tipo2->setName("Procesando");
        $tipo2->setOrden(2);
        $manager->persist($tipo2);
        $manager->flush();
        $this->addReference('estado-procesando', $tipo2);
        
        $tipo3 = new Status();
        $tipo3->setName("Enviado");
        $tipo3->setOrden(3);
        $manager->persist($tipo3);
        $manager->flush();
        $this->addReference('estado-enviado', $tipo3);
        
        $tipo4 = new Status();
        $tipo4->setName("Entregado");
        $tipo4->setOrden(4);
        $manager->persist($tipo4);
        $manager->flush();
        $this->addReference('estado-entregado', $tipo4);
        
        $tipo5 = new Status();
        $tipo5->setName("Cancelado");
        $tipo5->setOrden(5);
        $manager->persist($tipo5);
        $manager->flush();
        $this->addReference('estado-cancelado', $tipo5);
        
       
    }
    
    public function getOrder()
    {
        return 10; // the order in which fixtures will be loaded
    }
    
}

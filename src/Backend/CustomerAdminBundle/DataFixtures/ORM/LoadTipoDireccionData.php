<?php 
namespace Backend\CustomerAdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\CustomerAdminBundle\Entity\TipoDireccion;

class LoadTipoDireccionData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $tipo1 = new TipoDireccion();
        $tipo1->setName("Particular");
        $manager->persist($tipo1);
        $manager->flush();
        $this->addReference('tipo-particula', $tipo1);
        
        $tipo2 = new TipoDireccion();
        $tipo2->setName("Laboral");
        $manager->persist($tipo2);
        $manager->flush();
        $this->addReference('tipo-laboral', $tipo2);
        
        $tipo3 = new TipoDireccion();
        $tipo3->setName("Comercial");
        $manager->persist($tipo3);
        $manager->flush();
        $this->addReference('tipo-comercial', $tipo3);
        
       
    }
    
    public function getOrder()
    {
        return 10; // the order in which fixtures will be loaded
    }
    
}

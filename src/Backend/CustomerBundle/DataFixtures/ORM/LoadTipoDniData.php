<?php 
namespace Backend\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\CustomerBundle\Entity\TipoDni;

class LoadTipoDniData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $status1 = new TipoDni();
        $status1->setName("DNI");
        
        $manager->persist($status1);
        $manager->flush();
        $this->addReference('tipodni-dni', $status1);
        
        $status2 = new TipoDni();
        $status2->setName("LC");
        $manager->persist($status2);
        $manager->flush();
        $this->addReference('tipodni-libretacivica', $status2);
        
        $status3 = new TipoDni();
        $status3->setName("Pasaporte");
        $manager->persist($status3);
        $manager->flush();
        $this->addReference('tipodni-pasaporte', $status3);
        
        $status4 = new TipoDni();
        $status4->setName("LE");
        $manager->persist($status4);
        $manager->flush();
        $this->addReference('tipodni-enrolamiento', $status4);
    }
    
    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
    
}

<?php 
namespace Backend\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\CustomerBundle\Entity\Status;

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
        $status1 = new Status();
        $status1->setName("Pendiente");
        $manager->persist($status1);
        $manager->flush();
        $this->addReference('status-pendiente', $status1);
        
        $status2 = new Status();
        $status2->setName("Habilitado");
        $manager->persist($status2);
        $manager->flush();
        $this->addReference('statu-habilitado', $status2);
    }
    
    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }
    
}

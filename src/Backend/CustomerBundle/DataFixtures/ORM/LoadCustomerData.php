<?php 
namespace Backend\CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\CustomerBundle\Entity\Customer;

class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $userAdmin = new customer();
        $userAdmin->setPassword("123456");
        $userAdmin->setEmail('customer@admin.com');
        $userAdmin->setName("Customer");
        $userAdmin->setLastname("CustomerLast");
        $userAdmin->setStatus($this->getReference('status-pendiente'));
        $userAdmin->setIsActive("1");
        $userAdmin->setIsComercio("0");
        $userAdmin->setTipodni($this->getReference('tipodni-dni'));
        $userAdmin->setCreatedAt(new \DateTime('now'));
        $userAdmin->addGroup($this->getReference('cliente-group'));
        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference('customer-one', $userAdmin);
        
        $userAdmin1 = new customer();
        $userAdmin1->setPassword("123456");
        $userAdmin1->setEmail('comercio@admin.com');
        $userAdmin1->setName("Comercio");
        $userAdmin1->setLastname("ComercioLast");
        $userAdmin1->setTipodni($this->getReference('tipodni-dni'));
        $userAdmin1->setStatus($this->getReference('status-pendiente'));
        $userAdmin1->setIsActive("1");
        $userAdmin1->setIsComercio("1");
        $userAdmin1->setCreatedAt(new \DateTime('now'));
        $userAdmin1->addGroup($this->getReference('comercio-group'));
        $manager->persist($userAdmin1);
        $manager->flush();
        $this->addReference('customer-comercio', $userAdmin1);
    }
    
    public function getOrder()
    {
        return 17; // the order in which fixtures will be loaded
    }
    
}

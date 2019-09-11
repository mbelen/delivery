<?php 
namespace Backend\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Backend\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $userAdmin = new User();
        $userAdmin->setPassword("Adm1.687");
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setName("Admin");
        $userAdmin->setLastname("Admin");
        $userAdmin->setIsActive("1");
        $userAdmin->setCreatedAt(new \DateTime('now'));
        $userAdmin->addGroup($this->getReference('admin-group'));
        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference('admin-user', $userAdmin);
    }
    
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
    
}

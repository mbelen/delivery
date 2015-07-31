<?php 

namespace Backend\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Backend\UserBundle\Entity\Seteo;

class LoadSeteoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $seteoAdmin = new Seteo();
        $seteoAdmin->setName('email');
        $seteoAdmin->setValue('info@hope.com.ar');

        $manager->persist($seteoAdmin);
        $manager->flush();
        $this->addReference('email', $seteoAdmin);
        
        $seteoAdmin2 = new Seteo();
        $seteoAdmin2->setName('empresa');
        $seteoAdmin2->setValue('william hope');

        $manager->persist($seteoAdmin2);
        $manager->flush();
        $this->addReference('empresa', $seteoAdmin2);
        
        $seteoAdmin3 = new Seteo();
        $seteoAdmin3->setName('cercania');
        $seteoAdmin3->setValue('30');

        $manager->persist($seteoAdmin3);
        $manager->flush();
        $this->addReference('cercania', $seteoAdmin3);
        
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}

<?php 

namespace Backend\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Backend\AdminBundle\Entity\PayMethod;

class LoadPayMethodData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $pay = new PayMethod();
        $pay->setName('Efectivo');
        $manager->persist($pay);
        $manager->flush();
        $this->addReference('pay-efectivo', $pay);
        
         $pay = new PayMethod();
        $pay->setName('Posnet Inalambrico');
        $manager->persist($pay);
        $manager->flush();
        $this->addReference('pay-posnet', $pay);
        
         $pay = new PayMethod();
        $pay->setName('MercadoPago');
        $manager->persist($pay);
        $manager->flush();
        $this->addReference('pay-mercadopago', $pay);
       
        
        
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 11; // the order in which fixtures will be loaded
    }
}



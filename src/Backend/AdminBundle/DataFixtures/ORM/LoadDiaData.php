<?php 

namespace Backend\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Backend\AdminBundle\Entity\Dia;

class LoadDiaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dia = new Dia();
        $dia->setName('Lunes');
        $dia->setShort("L");
        $dia->setNro("1");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-lunes', $dia);
        
        
       $dia = new Dia();
        $dia->setName('Martes');
        $dia->setShort("M");
        $dia->setNro("2");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-martes', $dia);
        
        $dia = new Dia();
        $dia->setName('Miercoles');
        $dia->setShort("Mi");
        $dia->setNro("3");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-miercoles', $dia);
        
        $dia = new Dia();
        $dia->setName('Jueves');
        $dia->setShort("J");
        $dia->setNro("4");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-jueves', $dia);
        
        $dia = new Dia();
        $dia->setName('Viernes');
        $dia->setShort("V");
        $dia->setNro("5");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-viernes', $dia);
        
        $dia = new Dia();
        $dia->setName('Sabado');
        $dia->setShort("S");
        $dia->setNro("6");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-sabado', $dia);
        
        $dia = new Dia();
        $dia->setName('Domingo');
        $dia->setShort("D");
        $dia->setNro("7");
        $manager->persist($dia);
        $manager->flush();
        $this->addReference('dia-domingo', $dia);
        
       
        
        
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 13; // the order in which fixtures will be loaded
    }
}



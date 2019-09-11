<?php 

namespace Backend\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Backend\AdminBundle\Entity\Barrio;

class LoadBarrioData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $barrio = new Barrio();
        $barrio->setName('AGRONOMIA');
        $barrio->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio);
        $manager->flush();
        $this->addReference('barrio-agronomia', $barrio);
        
        $barrio1 = new Barrio();
        $barrio1->setName('ALMAGRO');
        $barrio1->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio1);
        $manager->flush();
        $this->addReference('barrio-almagro', $barrio1);
        
        $barrio2 = new Barrio();
        $barrio2->setName('MATADEROS');
        $barrio2->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio2);
        $manager->flush();
        $this->addReference('barrio-mataderos', $barrio2);
        
        $barrio3 = new Barrio();
        $barrio3->setName('LINIERS');
        $barrio3->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio3);
        $manager->flush();
        $this->addReference('barrio-liniers', $barrio3);
        
        $barrio4 = new Barrio();
        $barrio4->setName('Balvanera');
        $barrio4->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio4);
        $manager->flush();
        $this->addReference('barrio-balvanera', $barrio4);
        
        $barrio5 = new Barrio();
        $barrio5->setName('Barracas');
        $barrio5->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio5);
        $manager->flush();
        $this->addReference('barrio-barracas', $barrio5);
        
        $barrio6 = new Barrio();
        $barrio6->setName('Belgrano');
        $barrio6->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio6);
        $manager->flush();
        $this->addReference('barrio-belgrano', $barrio6);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Boca');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-boca', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Boedo');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-boedo', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Caballito');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-caballito', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Chacarita');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-chacarita', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Coghlan');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-coghlan', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Colegiales');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-colegiales', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Constitución');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-constitucion', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Flores');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-flores', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Floresta');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-floresta', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Monserrat');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-monserrat', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Monte Castro');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-montecastro', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Nueva Pompeya');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-nuevapompeya', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Nuñez');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-nunez', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Parque Avellaneda');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-parqueavellaneda', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Parque Chacabuco');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-parquechacabuco', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Parque Patricios');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-parquepatricios', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Paternal');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-paternal', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Puerto Madero');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-puertomadero', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Recoleta');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-recoleta', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Retiro');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-retiro', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Saavedra');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-saavedra', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('San Cristobal');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-sancristobal', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('San Nicolas');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-sannicolas', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('San Telmo');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-santelmo', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Velez Sarsfield');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-velezsarsfield', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Versalles');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-versalles', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa del Parque');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villaparque', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Devoto');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villadevoto', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Gral. Mitre');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villamitre', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Lugano');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villalugano', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Ortuzar');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villaortuzar', $barrio7);
        
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Pueyrredón');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villapueyrredon', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Real');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villareal', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Riachuelo');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villariachuelo', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Santa Rita');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villasantarita', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Soldati');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villasoldati', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Villa Urquiza');
        $barrio7->setZona($this->getReference('zona-capfed'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-villaurquiza', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Adrogué');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-adrogue', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Avellaneda');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-avellaneda', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Ayacucho');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-ayacucho', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Azul');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-azul', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Bahía Blanca');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-bahiablanca', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Balcarce');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-balcarce', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Baradero');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-baradero', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Benito Juárez');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-benitojuarez', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Berazategui');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-berazategui', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Berisso');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-berisso', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Bernal');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-bernal', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Bolívar');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-bolivar', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Bragado');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-bragado', $barrio7);
        
        
        $barrio7 = new Barrio();
        $barrio7->setName('Brandsen');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-brandsen', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Campana');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-campana', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Cañuelas');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-canuelas', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Carlos Casares');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-carloscasares', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Carlos Tejedor');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-carlostejedor', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Carmen de Areco');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-carmedeareco', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Castelli');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-castelli', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Chacabuco');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-chacabuco', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Chascomús');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-chascomus', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Chivilcoy');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-chivilcoy', $barrio7);
        
        $barrio7 = new Barrio();
        $barrio7->setName('Quilmes');
        $barrio7->setZona($this->getReference('zona-bsas'));
        $manager->persist($barrio7);
        $manager->flush();
        $this->addReference('barrio-quilmes', $barrio7);
        
     
        
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
}

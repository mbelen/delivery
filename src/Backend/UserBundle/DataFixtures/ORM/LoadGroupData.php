<?php 

namespace Backend\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Backend\UserBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $groupAdmin = new Group();
        $groupAdmin->setName('Administrador de sistemas');
        $groupAdmin->setRole('ROLE_ADMIN');
        $groupAdmin->addAcceso($this->getReference('add-user'));
        $groupAdmin->addAcceso($this->getReference('mod-user'));
        $groupAdmin->addAcceso($this->getReference('del-user'));
        $groupAdmin->addAcceso($this->getReference('view-user'));
        $groupAdmin->addAcceso($this->getReference('seteos'));
        $groupAdmin->addAcceso($this->getReference('add-zona'));
        $groupAdmin->addAcceso($this->getReference('mod-zona'));
        $groupAdmin->addAcceso($this->getReference('del-zona'));
        $groupAdmin->addAcceso($this->getReference('view-zona'));
        $groupAdmin->addAcceso($this->getReference('add-barrio'));
        $groupAdmin->addAcceso($this->getReference('mod-barrio'));
        $groupAdmin->addAcceso($this->getReference('del-barrio'));
        $groupAdmin->addAcceso($this->getReference('view-barrio'));
        
        $groupAdmin->addAcceso($this->getReference('add-categoria'));
        $groupAdmin->addAcceso($this->getReference('mod-categoria'));
        $groupAdmin->addAcceso($this->getReference('del-categoria'));
        $groupAdmin->addAcceso($this->getReference('view-categoria'));
        
        $groupAdmin->addAcceso($this->getReference('add-subcategoria'));
        $groupAdmin->addAcceso($this->getReference('mod-subcategoria'));
        $groupAdmin->addAcceso($this->getReference('del-subcategoria'));
        $groupAdmin->addAcceso($this->getReference('view-subcategoria'));
        
        $groupAdmin->addAcceso($this->getReference('add-customer'));
        $groupAdmin->addAcceso($this->getReference('mod-customer'));
        $groupAdmin->addAcceso($this->getReference('del-customer'));
        $groupAdmin->addAcceso($this->getReference('view-customer'));
        
        $groupAdmin->addAcceso($this->getReference('add-direccion'));
        $groupAdmin->addAcceso($this->getReference('mod-direccion'));
        $groupAdmin->addAcceso($this->getReference('del-direccion'));
        $groupAdmin->addAcceso($this->getReference('view-direccion'));
        

        $groupAdmin->addAcceso($this->getReference('add-metodopago'));
        $groupAdmin->addAcceso($this->getReference('mod-metodopago'));
        $groupAdmin->addAcceso($this->getReference('del-metodopago'));
        $groupAdmin->addAcceso($this->getReference('view-metodopago'));

        
        $groupAdmin->addAcceso($this->getReference('add-sucursal'));
        $groupAdmin->addAcceso($this->getReference('mod-sucursal'));
        $groupAdmin->addAcceso($this->getReference('del-sucursal'));
        $groupAdmin->addAcceso($this->getReference('view-sucursal'));

        
        $groupAdmin->addAcceso($this->getReference('add-producto'));
        $groupAdmin->addAcceso($this->getReference('mod-producto'));
        $groupAdmin->addAcceso($this->getReference('del-producto'));
        $groupAdmin->addAcceso($this->getReference('view-producto'));
        
        $groupAdmin->addAcceso($this->getReference('add-venta'));
        $groupAdmin->addAcceso($this->getReference('mod-venta'));
        $groupAdmin->addAcceso($this->getReference('del-venta'));
        $groupAdmin->addAcceso($this->getReference('view-venta'));
        
        $groupAdmin->addAcceso($this->getReference('add-compra'));
        $groupAdmin->addAcceso($this->getReference('mod-compra'));
        $groupAdmin->addAcceso($this->getReference('del-compra'));
        $groupAdmin->addAcceso($this->getReference('view-compra'));
        
        $groupAdmin->addAcceso($this->getReference('add-favorito'));
        $groupAdmin->addAcceso($this->getReference('mod-favorito'));
        $groupAdmin->addAcceso($this->getReference('del-favorito'));
        $groupAdmin->addAcceso($this->getReference('view-favorito'));
        
        $groupAdmin->addAcceso($this->getReference('add-horario'));
        $groupAdmin->addAcceso($this->getReference('mod-horario'));
        $groupAdmin->addAcceso($this->getReference('del-horario'));
        $groupAdmin->addAcceso($this->getReference('view-horario'));
       
        $groupAdmin->addAcceso($this->getReference('add-variedad'));
        $groupAdmin->addAcceso($this->getReference('mod-variedad'));
        $groupAdmin->addAcceso($this->getReference('del-variedad'));
        $groupAdmin->addAcceso($this->getReference('view-variedad')); 



        
        $manager->persist($groupAdmin);
        $manager->flush();
        $this->addReference('admin-group', $groupAdmin);
        
        //aun no tiene roles asignados
        $groupAdmin1 = new Group();
        $groupAdmin1->setName('Extra Comercio');
        $groupAdmin1->setRole('ROLE_EXTRACOMERCIO');
        $groupAdmin1->addAcceso($this->getReference('importar-producto'));
        $groupAdmin1->addAcceso($this->getReference('edicion-producto'));

        
        
        $manager->persist($groupAdmin1);
        $manager->flush();
        $this->addReference('extracomercio-group', $groupAdmin1);

        
        
        $groupAdmin2 = new Group();
        $groupAdmin2->setName('Comercio');
        $groupAdmin2->setRole('ROLE_COMERCIO');
        $groupAdmin2->addAcceso($this->getReference('add-direccion'));
        $groupAdmin2->addAcceso($this->getReference('mod-direccion'));
        $groupAdmin2->addAcceso($this->getReference('del-direccion'));
        $groupAdmin2->addAcceso($this->getReference('view-direccion'));
        
        $groupAdmin2->addAcceso($this->getReference('add-producto'));
        $groupAdmin2->addAcceso($this->getReference('mod-producto'));
        $groupAdmin2->addAcceso($this->getReference('del-producto'));
        $groupAdmin2->addAcceso($this->getReference('view-producto'));
        
        $groupAdmin2->addAcceso($this->getReference('add-sucursal'));
        $groupAdmin2->addAcceso($this->getReference('mod-sucursal'));
        $groupAdmin2->addAcceso($this->getReference('del-sucursal'));
        $groupAdmin2->addAcceso($this->getReference('view-sucursal'));
        $groupAdmin2->addAcceso($this->getReference('add-variedad'));
        $groupAdmin2->addAcceso($this->getReference('mod-variedad'));
        $groupAdmin2->addAcceso($this->getReference('del-variedad'));
        $groupAdmin2->addAcceso($this->getReference('view-variedad'));
        $groupAdmin2->addAcceso($this->getReference('view-favorito'));
        $groupAdmin2->addAcceso($this->getReference('del-favorito'));
        $groupAdmin2->addAcceso($this->getReference('view-compra'));
        $groupAdmin2->addAcceso($this->getReference('view-venta'));
        $groupAdmin2->addAcceso($this->getReference('add-horario'));
        $groupAdmin2->addAcceso($this->getReference('mod-horario'));
        $groupAdmin2->addAcceso($this->getReference('del-horario'));
        $groupAdmin2->addAcceso($this->getReference('view-horario'));
        $groupAdmin2->addAcceso($this->getReference('add-region'));
        $groupAdmin2->addAcceso($this->getReference('mod-region'));
        $groupAdmin2->addAcceso($this->getReference('del-region'));
        $groupAdmin2->addAcceso($this->getReference('view-region')); 
        
        $manager->persist($groupAdmin2);
        $manager->flush();
        $this->addReference('comercio-group', $groupAdmin2); 
        
        $groupAdmin3 = new Group();
        $groupAdmin3->setName('Cliente');
        $groupAdmin3->setRole('ROLE_CLIENTE');
        $groupAdmin3->addAcceso($this->getReference('add-direccion'));
        $groupAdmin3->addAcceso($this->getReference('mod-direccion'));
        $groupAdmin3->addAcceso($this->getReference('del-direccion'));
        $groupAdmin3->addAcceso($this->getReference('view-direccion'));
        $groupAdmin3->addAcceso($this->getReference('view-favorito'));
        $groupAdmin3->addAcceso($this->getReference('del-favorito'));
        $groupAdmin3->addAcceso($this->getReference('view-compra'));
        
        
        
        $manager->persist($groupAdmin3);
        $manager->flush();
        $this->addReference('cliente-group', $groupAdmin3);
        
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}

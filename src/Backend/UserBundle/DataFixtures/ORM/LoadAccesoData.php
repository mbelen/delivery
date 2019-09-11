<?php 

namespace Backend\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Backend\UserBundle\Entity\Acceso;

class LoadAccesoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $accesoAdmin = new Acceso();
        $accesoAdmin->setName('Nuevo Usuario');
        $accesoAdmin->setAcceso('ROLE_ADDUSER');
        $manager->persist($accesoAdmin);
        $manager->flush();
        $this->addReference('add-user', $accesoAdmin);
        
        $accesoAdmin2 = new Acceso();
        $accesoAdmin2->setName('Modificar Usuario');
        $accesoAdmin2->setAcceso('ROLE_MODUSER');
        $manager->persist($accesoAdmin2);
        $manager->flush();
        $this->addReference('mod-user', $accesoAdmin2);

        $accesoAdmin3 = new Acceso();
        $accesoAdmin3->setName('Borrar Usuario');
        $accesoAdmin3->setAcceso('ROLE_DELUSER');
        $manager->persist($accesoAdmin3);
        $manager->flush();
        $this->addReference('del-user', $accesoAdmin3);
       
        $accesoAdmin4 = new Acceso();
        $accesoAdmin4->setName('Listar Usuarios');
        $accesoAdmin4->setAcceso('ROLE_VIEWUSER');
        $manager->persist($accesoAdmin4);
        $manager->flush();
        $this->addReference('view-user', $accesoAdmin4);
        
        $accesoAdmin5 = new Acceso();
        $accesoAdmin5->setName('Seteos de parámetros');
        $accesoAdmin5->setAcceso('ROLE_SETEO');
        $manager->persist($accesoAdmin5);
        $manager->flush();
        $this->addReference('seteos', $accesoAdmin5);
        
        $accesoAdmin6 = new Acceso();
        $accesoAdmin6->setName('Nueva Zona');
        $accesoAdmin6->setAcceso('ROLE_ADDZONA');
        $manager->persist($accesoAdmin6);
        $manager->flush();
        $this->addReference('add-zona', $accesoAdmin6);
        
        $accesoAdmin7 = new Acceso();
        $accesoAdmin7->setName('Modificar Zona');
        $accesoAdmin7->setAcceso('ROLE_MODZONA');
        $manager->persist($accesoAdmin7);
        $manager->flush();
        $this->addReference('mod-zona', $accesoAdmin7);

        $accesoAdmin8 = new Acceso();
        $accesoAdmin8->setName('Borrar Zona');
        $accesoAdmin8->setAcceso('ROLE_DELZONA');
        $manager->persist($accesoAdmin8);
        $manager->flush();
        $this->addReference('del-zona', $accesoAdmin8);
       
        $accesoAdmin9 = new Acceso();
        $accesoAdmin9->setName('Listar Zona');
        $accesoAdmin9->setAcceso('ROLE_VIEWZONA');
        $manager->persist($accesoAdmin9);
        $manager->flush();
        $this->addReference('view-zona', $accesoAdmin9);
        
        $accesoAdmin10 = new Acceso();
        $accesoAdmin10->setName('Nueva Categoría');
        $accesoAdmin10->setAcceso('ROLE_ADDCATEGORIA');
        $manager->persist($accesoAdmin10);
        $manager->flush();
        $this->addReference('add-categoria', $accesoAdmin10);
        
        $accesoAdmin11 = new Acceso();
        $accesoAdmin11->setName('Modificar Categoría');
        $accesoAdmin11->setAcceso('ROLE_MODCATEGORIA');
        $manager->persist($accesoAdmin11);
        $manager->flush();
        $this->addReference('mod-categoria', $accesoAdmin11);

        $accesoAdmin12 = new Acceso();
        $accesoAdmin12->setName('Borrar Categoría');
        $accesoAdmin12->setAcceso('ROLE_DELCATEGORIA');
        $manager->persist($accesoAdmin12);
        $manager->flush();
        $this->addReference('del-categoria', $accesoAdmin12);
       
        $accesoAdmin13 = new Acceso();
        $accesoAdmin13->setName('Listar Categorías');
        $accesoAdmin13->setAcceso('ROLE_VIEWCATEGORIA');
        $manager->persist($accesoAdmin13);
        $manager->flush();
        $this->addReference('view-categoria', $accesoAdmin13);
        
        $accesoAdmin14 = new Acceso();
        $accesoAdmin14->setName('Nueva Subcategoría');
        $accesoAdmin14->setAcceso('ROLE_ADDSUBCATEGORIA');
        $manager->persist($accesoAdmin14);
        $manager->flush();
        $this->addReference('add-subcategoria', $accesoAdmin14);
        
        $accesoAdmin15 = new Acceso();
        $accesoAdmin15->setName('Modificar Subcategoría');
        $accesoAdmin15->setAcceso('ROLE_MODSUBCATEGORIA');
        $manager->persist($accesoAdmin15);
        $manager->flush();
        $this->addReference('mod-subcategoria', $accesoAdmin15);

        $accesoAdmin16 = new Acceso();
        $accesoAdmin16->setName('Borrar Subcategoría');
        $accesoAdmin16->setAcceso('ROLE_DELSUBCATEGORIA');
        $manager->persist($accesoAdmin16);
        $manager->flush();
        $this->addReference('del-subcategoria', $accesoAdmin16);
       
        $accesoAdmin17 = new Acceso();
        $accesoAdmin17->setName('Listar Subcategorías');
        $accesoAdmin17->setAcceso('ROLE_VIEWSUBCATEGORIA');
        $manager->persist($accesoAdmin17);
        $manager->flush();
        $this->addReference('view-subcategoria', $accesoAdmin17);
        
        $accesoAdmin18 = new Acceso();
        $accesoAdmin18->setName('Nuevo Barrio');
        $accesoAdmin18->setAcceso('ROLE_ADDBARRIO');
        $manager->persist($accesoAdmin18);
        $manager->flush();
        $this->addReference('add-barrio', $accesoAdmin18);
        
        $accesoAdmin19 = new Acceso();
        $accesoAdmin19->setName('Modificar Barrio');
        $accesoAdmin19->setAcceso('ROLE_MODBARRIO');
        $manager->persist($accesoAdmin19);
        $manager->flush();
        $this->addReference('mod-barrio', $accesoAdmin19);

        $accesoAdmin20 = new Acceso();
        $accesoAdmin20->setName('Borrar Barrio');
        $accesoAdmin20->setAcceso('ROLE_DELBARRIO');
        $manager->persist($accesoAdmin20);
        $manager->flush();
        $this->addReference('del-barrio', $accesoAdmin20);
       
        $accesoAdmin21= new Acceso();
        $accesoAdmin21->setName('Listar Barrio');
        $accesoAdmin21->setAcceso('ROLE_VIEWBARRIO');
        $manager->persist($accesoAdmin21);
        $manager->flush();
        $this->addReference('view-barrio', $accesoAdmin21);
        
        $accesoAdmin22 = new Acceso();
        $accesoAdmin22->setName('Nuevo Cliente');
        $accesoAdmin22->setAcceso('ROLE_ADDCUSTOMER');
        $manager->persist($accesoAdmin22);
        $manager->flush();
        $this->addReference('add-customer', $accesoAdmin22);
        
        $accesoAdmin23 = new Acceso();
        $accesoAdmin23->setName('Modificar Cliente');
        $accesoAdmin23->setAcceso('ROLE_MODCUSTOMER');
        $manager->persist($accesoAdmin23);
        $manager->flush();
        $this->addReference('mod-customer', $accesoAdmin23);

        $accesoAdmin24 = new Acceso();
        $accesoAdmin24->setName('Borrar Cliente');
        $accesoAdmin24->setAcceso('ROLE_DELCUSTOMER');
        $manager->persist($accesoAdmin24);
        $manager->flush();
        $this->addReference('del-customer', $accesoAdmin24);
       
        $accesoAdmin25= new Acceso();
        $accesoAdmin25->setName('Listar Cliente');
        $accesoAdmin25->setAcceso('ROLE_VIEWCUSTOMER');
        $manager->persist($accesoAdmin25);
        $manager->flush();
        $this->addReference('view-customer', $accesoAdmin25); 
			
        $accesoAdmin26 = new Acceso();
        $accesoAdmin26->setName('Nueva Direccion');
        $accesoAdmin26->setAcceso('ROLE_ADDDIRECCION');
        $manager->persist($accesoAdmin26);
        $manager->flush();
        $this->addReference('add-direccion', $accesoAdmin26);
        
        $accesoAdmin27 = new Acceso();
        $accesoAdmin27->setName('Modificar Direccion');
        $accesoAdmin27->setAcceso('ROLE_MODDIRECCION');
        $manager->persist($accesoAdmin27);
        $manager->flush();
        $this->addReference('mod-direccion', $accesoAdmin27);

        $accesoAdmin28 = new Acceso();
        $accesoAdmin28->setName('Borrar Direccion');
        $accesoAdmin28->setAcceso('ROLE_DELDIRECCION');
        $manager->persist($accesoAdmin28);
        $manager->flush();
        $this->addReference('del-direccion', $accesoAdmin28);
       
        $accesoAdmin29= new Acceso();
        $accesoAdmin29->setName('Listar Direcciones');
        $accesoAdmin29->setAcceso('ROLE_VIEWDIRECCION');
        $manager->persist($accesoAdmin29);
        $manager->flush();
        $this->addReference('view-direccion', $accesoAdmin29);
        
        $accesoAdmin30 = new Acceso();
        $accesoAdmin30->setName('Nuevo Producto');
        $accesoAdmin30->setAcceso('ROLE_ADDPRODUCTO');
        $manager->persist($accesoAdmin30);
        $manager->flush();
        $this->addReference('add-producto', $accesoAdmin30);
        
        $accesoAdmin31 = new Acceso();
        $accesoAdmin31->setName('Modificar Producto');
        $accesoAdmin31->setAcceso('ROLE_MODPRODUCTO');
        $manager->persist($accesoAdmin31);
        $manager->flush();
        $this->addReference('mod-producto', $accesoAdmin31);

        $accesoAdmin32 = new Acceso();
        $accesoAdmin32->setName('Borrar Producto');
        $accesoAdmin32->setAcceso('ROLE_DELPRODUCTO');
        $manager->persist($accesoAdmin32);
        $manager->flush();
        $this->addReference('del-producto', $accesoAdmin32);
       
        $accesoAdmin33= new Acceso();
        $accesoAdmin33->setName('Listar Producto');
        $accesoAdmin33->setAcceso('ROLE_VIEWPRODUCTO');
        $manager->persist($accesoAdmin33);
        $manager->flush();
        $this->addReference('view-producto', $accesoAdmin33); 
        
        $accesoAdmin34 = new Acceso();
        $accesoAdmin34->setName('Nueva Sucursal');
        $accesoAdmin34->setAcceso('ROLE_ADDSUCURSAL');
        $manager->persist($accesoAdmin34);
        $manager->flush();
        $this->addReference('add-sucursal', $accesoAdmin34);
        
        $accesoAdmin35 = new Acceso();
        $accesoAdmin35->setName('Modificar Sucursal');
        $accesoAdmin35->setAcceso('ROLE_MODSUCURSAL');
        $manager->persist($accesoAdmin35);
        $manager->flush();
        $this->addReference('mod-sucursal', $accesoAdmin35);

        $accesoAdmin36 = new Acceso();
        $accesoAdmin36->setName('Borrar Sucursal');
        $accesoAdmin36->setAcceso('ROLE_DELSUCURSAL');
        $manager->persist($accesoAdmin36);
        $manager->flush();
        $this->addReference('del-sucursal', $accesoAdmin36);
       
        $accesoAdmin37= new Acceso();
        $accesoAdmin37->setName('Listar Sucursal');
        $accesoAdmin37->setAcceso('ROLE_VIEWSUCURSAL');
        $manager->persist($accesoAdmin37);
        $manager->flush();
        $this->addReference('view-sucursal', $accesoAdmin37);
        
        $accesoAdmin38 = new Acceso();
        $accesoAdmin38->setName('Nuevo Compra');
        $accesoAdmin38->setAcceso('ROLE_ADDCOMPRA');
        $manager->persist($accesoAdmin38);
        $manager->flush();
        $this->addReference('add-compra', $accesoAdmin38);
        
        $accesoAdmin39 = new Acceso();
        $accesoAdmin39->setName('Modificar Compra');
        $accesoAdmin39->setAcceso('ROLE_MODCOMPRA');
        $manager->persist($accesoAdmin39);
        $manager->flush();
        $this->addReference('mod-compra', $accesoAdmin39);

        $accesoAdmin40 = new Acceso();
        $accesoAdmin40->setName('Borrar Compra');
        $accesoAdmin40->setAcceso('ROLE_DELCOMPRA');
        $manager->persist($accesoAdmin40);
        $manager->flush();
        $this->addReference('del-compra', $accesoAdmin40);
       
        $accesoAdmin41= new Acceso();
        $accesoAdmin41->setName('Listar Compra');
        $accesoAdmin41->setAcceso('ROLE_VIEWCOMPRA');
        $manager->persist($accesoAdmin41);
        $manager->flush();
        $this->addReference('view-compra', $accesoAdmin41);
         
	      $accesoAdmin42 = new Acceso();
        $accesoAdmin42->setName('Nueva Venta');
        $accesoAdmin42->setAcceso('ROLE_ADDVENTA');
        $manager->persist($accesoAdmin42);
        $manager->flush();
        $this->addReference('add-venta', $accesoAdmin42);
        
        

        $accesoAdmin43 = new Acceso();
        $accesoAdmin43->setName('Borrar Venta');
        $accesoAdmin43->setAcceso('ROLE_DELVENTA');
        $manager->persist($accesoAdmin43);
        $manager->flush();
        $this->addReference('del-venta', $accesoAdmin43);
       
        $accesoAdmin44= new Acceso();
        $accesoAdmin44->setName('Listar Venta');
        $accesoAdmin44->setAcceso('ROLE_VIEWVENTA');
        $manager->persist($accesoAdmin44);
        $manager->flush();
        $this->addReference('view-venta', $accesoAdmin44); 
  
       $accesoAdmin45 = new Acceso();
        $accesoAdmin45->setName('Modificar Venta');
        $accesoAdmin45->setAcceso('ROLE_MODVENTA');
        $manager->persist($accesoAdmin45);
        $manager->flush();
        $this->addReference('mod-venta', $accesoAdmin45);
        
        
        $accesoAdmin46 = new Acceso();
        $accesoAdmin46->setName('Nuevo Favorito');
        $accesoAdmin46->setAcceso('ROLE_ADDFAVORITO');
        $manager->persist($accesoAdmin46);
        $manager->flush();
        $this->addReference('add-favorito', $accesoAdmin46);
        
        

        $accesoAdmin47 = new Acceso();
        $accesoAdmin47->setName('Borrar Favorito');
        $accesoAdmin47->setAcceso('ROLE_DELFAVORITO');
        $manager->persist($accesoAdmin47);
        $manager->flush();
        $this->addReference('del-favorito', $accesoAdmin47);
       
        $accesoAdmin48= new Acceso();
        $accesoAdmin48->setName('Listar Favoritos');
        $accesoAdmin48->setAcceso('ROLE_VIEWFAVORITO');
        $manager->persist($accesoAdmin48);
        $manager->flush();
        $this->addReference('view-favorito', $accesoAdmin48); 
  
       $accesoAdmin49 = new Acceso();
        $accesoAdmin49->setName('Modificar Favorito');
        $accesoAdmin49->setAcceso('ROLE_MODFAVORITO');
        $manager->persist($accesoAdmin49);
        $manager->flush();
        $this->addReference('mod-favorito', $accesoAdmin49);
        
        
        $accesoAdmin50 = new Acceso();
        $accesoAdmin50->setName('Nueva Variedad');
        $accesoAdmin50->setAcceso('ROLE_ADDVARIEDAD');
        $manager->persist($accesoAdmin50);
        $manager->flush();
        $this->addReference('add-variedad', $accesoAdmin50);
        
        

        $accesoAdmin51 = new Acceso();
        $accesoAdmin51->setName('Borrar Variedad');
        $accesoAdmin51->setAcceso('ROLE_DELVARIEDAD');
        $manager->persist($accesoAdmin51);
        $manager->flush();
        $this->addReference('del-variedad', $accesoAdmin51);
       
        $accesoAdmin52= new Acceso();
        $accesoAdmin52->setName('Listar Variedad');
        $accesoAdmin52->setAcceso('ROLE_VIEWVARIEDAD');
        $manager->persist($accesoAdmin52);
        $manager->flush();
        $this->addReference('view-variedad', $accesoAdmin52); 
  
       $accesoAdmin53 = new Acceso();
        $accesoAdmin53->setName('Modificar Variedad');
        $accesoAdmin53->setAcceso('ROLE_MODVARIEDAD');
        $manager->persist($accesoAdmin53);
        $manager->flush();
        $this->addReference('mod-variedad', $accesoAdmin53);
        
        $accesoAdmin58 = new Acceso();
        $accesoAdmin58->setName('Nuevo M Pago');
        $accesoAdmin58->setAcceso('ROLE_ADDMETODOPAGO');
        $manager->persist($accesoAdmin58);
        $manager->flush();
        $this->addReference('add-metodopago', $accesoAdmin58);
        
        $accesoAdmin59 = new Acceso();
        $accesoAdmin59->setName('Modificar M Pago');
        $accesoAdmin59->setAcceso('ROLE_MODMETODOPAGO');
        $manager->persist($accesoAdmin59);
        $manager->flush();
        $this->addReference('mod-metodopago', $accesoAdmin59);

        $accesoAdmin60 = new Acceso();
        $accesoAdmin60->setName('Borrar  M Pago');
        $accesoAdmin60->setAcceso('ROLE_DELMETODOPAGO');
        $manager->persist($accesoAdmin60);
        $manager->flush();
        $this->addReference('del-metodopago', $accesoAdmin60);
       
        $accesoAdmin61= new Acceso();
        $accesoAdmin61->setName('Listar  M Pago');
        $accesoAdmin61->setAcceso('ROLE_VIEWMETODOPAGO');
        $manager->persist($accesoAdmin61);
        $manager->flush();
        $this->addReference('view-metodopago', $accesoAdmin61);
        
        $accesoAdmin62 = new Acceso();
        $accesoAdmin62->setName('Nuevo Horario');
        $accesoAdmin62->setAcceso('ROLE_ADDHORARIO');
        $manager->persist($accesoAdmin62);
        $manager->flush();
        $this->addReference('add-horario', $accesoAdmin62);
        
        $accesoAdmin63 = new Acceso();
        $accesoAdmin63->setName('Modificar Horario');
        $accesoAdmin63->setAcceso('ROLE_MODHORARIO');
        $manager->persist($accesoAdmin63);
        $manager->flush();
        $this->addReference('mod-horario', $accesoAdmin63);

        $accesoAdmin64 = new Acceso();
        $accesoAdmin64->setName('Borrar  Horario');
        $accesoAdmin64->setAcceso('ROLE_DELHORARIO');
        $manager->persist($accesoAdmin64);
        $manager->flush();
        $this->addReference('del-horario', $accesoAdmin64);
       
        $accesoAdmin65= new Acceso();
        $accesoAdmin65->setName('Listar Horario');
        $accesoAdmin65->setAcceso('ROLE_VIEWHORARIO');
        $manager->persist($accesoAdmin65);
        $manager->flush();
        $this->addReference('view-horario', $accesoAdmin65);

		$accesoAdmin66= new Acceso();
        $accesoAdmin66->setName('Importar Productos');
        $accesoAdmin66->setAcceso('ROLE_IMPORTAR');
        $manager->persist($accesoAdmin66);
        $manager->flush();
        $this->addReference('importar-producto', $accesoAdmin66);

        $accesoAdmin67= new Acceso();
        $accesoAdmin67->setName('Edición Masiva Productos');
        $accesoAdmin67->setAcceso('ROLE_EDICION');
        $manager->persist($accesoAdmin67);
        $manager->flush();
        $this->addReference('edicion-producto', $accesoAdmin67);

        $accesoAdmin68 = new Acceso();
        $accesoAdmin68->setName('Nueva Región');
        $accesoAdmin68->setAcceso('ROLE_ADDREGION');
        $manager->persist($accesoAdmin68);
        $manager->flush();
        $this->addReference('add-region', $accesoAdmin68);
        
        $accesoAdmin69 = new Acceso();
        $accesoAdmin69->setName('Modificar Región');
        $accesoAdmin69->setAcceso('ROLE_MODREGION');
        $manager->persist($accesoAdmin69);
        $manager->flush();
        $this->addReference('mod-region', $accesoAdmin69);

        $accesoAdmin70 = new Acceso();
        $accesoAdmin70->setName('Borrar  Región');
        $accesoAdmin70->setAcceso('ROLE_DELREGION');
        $manager->persist($accesoAdmin70);
        $manager->flush();
        $this->addReference('del-region', $accesoAdmin70);
       
        $accesoAdmin71= new Acceso();
        $accesoAdmin71->setName('Listar  Región');
        $accesoAdmin71->setAcceso('ROLE_VIEWREGION');
        $manager->persist($accesoAdmin71);
        $manager->flush();
        $this->addReference('view-region', $accesoAdmin71);
               
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}

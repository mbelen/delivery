<?php

namespace Frontend\HomeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\HttpFoundation\Session\Session;
use Backend\CustomerAdminBundle\Entity\Producto;
use Backend\CustomerAdminBundle\Entity\Sucursal;
use Backend\CustomerAdminBundle\Entity\Direccion;
use Backend\CustomerAdminBundle\Entity\Pedido;
use Backend\CustomerAdminBundle\Entity\Proceso;
use Backend\CustomerAdminBundle\Entity\Detalle;

/**
 * Producto controller.
 *
 */


class Point {
   private $X = '';
   private $Y = '';
   
   public function getX(){
      return $this->X;
   }

   public function setX($x){
      $this->X = $x;
   }

   public function getY(){
      return $this->Y;
   }

   public function setY($y){
      $this->Y = $y;
   }


};

class ProductoController extends Controller
{

   public function getVariedadesByProductoAction($id){
	
	    //$sucursal_id = $request->request->get("sucursal");
        if($id){

            $em = $this->getDoctrine()->getManager();

            $producto = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($id);

            $variedades = $producto->getVariedades();


           return $this->render('FrontendHomeBundle:Shop:index.html.twig', array(
            'variedades' => $variedades
            //'productos' => $productos

           ));

        }else{

            return $this->render('FrontendHomeBundle:Home:terminos.html.twig');
        }
    }
   
  
    

    
    public function showCarritoAction(){
        $session = $this->getRequest()->getSession();
        if ($session->get("login")){
      	   return $this->render('FrontendHomeBundle:Cart:index.html.twig');
        }else{
           return $this->redirect($this->generateUrl('frontend_homepage'));
        }
    
    }
    
    private function checkSucursalIsResctricted($subcategorias){
    
       $isRestricted = false;
       foreach($subcategorias as $s){
         
          if ($s->getCategoria()->getIsRestrict()){
            $isRestricted = true;
          }
       }
       return $isRestricted;
    
    }

    /**
     * @param $search
     * @param $id
     * @return Response
     */

    public function getProductsByTiendaFilterAction($id,$search){

        $resultado = array();

        if($id) {

            $em = $this->getDoctrine()->getManager();
            $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);
            $horarios = $sucursal->getHorarios();

            $dql = "SELECT p FROM BackendCustomerAdminBundle:Producto p JOIN p.sucursales s WHERE s.id =".$id;

            if($search) {

             $dql.=  " AND p.name like '%".trim($search)."%'";

            }
            $query = $em->createQuery($dql);
            $productos = $query->getResult();
            $resultado = $productos;
            $count = count($resultado);
        }
        $restricted = $this->checkSucursalIsResctricted($sucursal->getSubcategorias());
        //$promos = $sucursal->getPromociones();  
        $promos = array();
        $items = array();
        return $this->render('FrontendHomeBundle:Shop:index.html.twig', array(
            'restricted' => $restricted,
            'tienda' => $sucursal,
            'productos' => $resultado,
            'subcategoria' => $search,
            'count' => $count,
            'search'=>$search,
            'horarios' =>$horarios,
            'promos' => $promos,
            'items'  => $items
        ));

    }


   public function getDireccionesAction(Request $request){
        
      $session = $this->getRequest()->getSession();
      $direcciones=array();
      $customer = $session->get("customer"); 
      $em = $this->getDoctrine()->getManager();
      $user=$em->getRepository('BackendCustomerBundle:Customer')->find($customer->getId());
      
      foreach ($user->getDirecciones() as $d){
           
           $direcciones[] = array("id"=>$d->getId(),"name"=>$d->getFull(), "lat"=>$d->getLat(),"lon"=>$d->getLon());
      }
      
      
      $response = new Response(json_encode($direcciones));
        
      $response->headers->set('Content-Type', 'application/json');
  
      return $response;
   
   }
   
   public function getVariedadesAction(Request $request){
   
     $em = $this->getDoctrine()->getManager();
     $producto = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($request->get("producto"));

     $variedades = $producto->getVariedades();
     
     $listado=array();
     foreach($variedades as $v){
         $listado[]=array("id"=>$v->getId(),"name"=>$v->getName());
     }
     
      $response = new Response(json_encode($listado));
        
      $response->headers->set('Content-Type', 'application/json');
  
      return $response;
   }
   
   public function getZonasAction(Request $request){
       $zonas = $this->getDoctrine()->getRepository('BackendAdminBundle:Zona')->findAll();

        $listado=array();
        foreach($zonas as $z){
              $record=array();
              $record["id"]=$z->getId();
              $record["name"]=$z->getName();
              $listado[] = $record;
         }
         $response = new Response(json_encode($listado));

         $response->headers->set('Content-Type', 'application/json');

         return $response;
   
   }
   
   public function getBarriosAction(Request $request){
    $zonaId = $request->get("zona");
   
    $barrios = $this->getDoctrine()->getRepository('BackendAdminBundle:Barrio')->findBy(array("zona"=>$zonaId));

        $resultado=array();
        foreach($barrios as $b){
              $record=array();
              $record["id"]=$b->getId();
              $record["name"]=$b->getName();
              $resultado[] = $record;
         }

         $response = new Response(json_encode($resultado));

         $response->headers->set('Content-Type', 'application/json');

         return $response;
   }

   public function llegaAction(Request $request){

      $session = $this->getRequest()->getSession();
      $resultado=array("status"=>1,"message"=>'No llega');
      $em = $this->getDoctrine()->getManager();
      $lat=0;
      $long=0;
      if ($request->get("direccion") != 0){
        //verificar si llega con la direccion real
          $direccionId=$request->get("direccion");
          $direccion=$em->getRepository('BackendCustomerAdminBundle:Direccion')->find($direccionId);
          $lat=$direccion->getLat();
          $long=$direccion->getLon();
          $resultado["direccion"] = $direccion->getFull();
          $resultado["direccionid"] = $direccionId;
      }else{
          $lat= $request->get("lat");
          $long=$request->get("lon"); 
      }
      $tienda=$request->get("tienda");
      

       
       $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($tienda);

       $llega = $this->calculoDistancia($sucursal,$lat,$long);

        if($llega) {
              //agrear la direccion para el cliente
              if ($request->get("direccion") == 0){
                  $customerId = $session->get("customer")->getId();
                  $customer=$em->getRepository('BackendCustomerBundle:Customer')->find($customerId);
                  $zona = $em->getRepository('BackendAdminBundle:Zona')->find($request->get("zona"));
                  $barrio = $em->getRepository('BackendAdminBundle:Barrio')->find($request->get("barrio"));
                  $tipo =$em->getRepository('BackendCustomerAdminBundle:TipoDireccion')->findOneByName("Particular"); 
                  $entity  = new Direccion();
                  $entity->setCalle($request->get("calle"));
                  $entity->setNumero($request->get("nro"));
                  if ($request->get("piso")){
                    $entity->setPiso($request->get("piso"));
                  }
                  if ($request->get("dto")){
                    $entity->setDepto($request->get("dto"));
                  } 
                  $entity->setLat($lat);
                  $entity->setLon($long);
                  $entity->setZona($zona);
                  $entity->setBarrio($barrio);
                  $entity->addCustomer($customer);
                  $entity->setTipo($tipo);
                  $em->persist($entity);
                  $em->flush();
                  $resultado["direccion"] = $entity->getFull();
                  $resultado["direccionid"] = $entity->getId();
              }
              $resultado["status"] = 0;
              $resultado["message"] = 'llega';
              
        }
      
      $response = new Response(json_encode($resultado));
        
      $response->headers->set('Content-Type', 'application/json');
  
      return $response;
   
   }

   /*
    *  Calcula si está dentro del radio de entrega
    */

   private function calculoDistancia(Sucursal $s, $lat2, $long2){

           $regiones = $s->getRegiones();
           if (count($regiones) == 0){  //verifico por radio

           $direccion = $s->getDireccion();

           $lat1 = $direccion->getLat();
           $long1 = $direccion->getLon();
           $radio = $s->getRadio();

           $degtorad = 0.01745329;
           $radtodeg = 57.29577951;

           $dlong = ($long1 - $long2);
           $dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad))
               + (cos($lat1 * $degtorad) * cos($lat2 * $degtorad)
                   * cos($dlong * $degtorad));

           $dd = acos($dvalue) * $radtodeg;

           $km = ($dd * 111.302);

          if($km <= $radio){
                return true;
          }

           return false;
         }else{ //verifico si llega por regiones
            
             $p= new Point();
             $p->setX($lat2);
             $p->setY($long2);

            foreach($regiones as $r){

               if ( $this->pointInZone($r->getCoordenadas(),$p) ){  //si da positivo en una zona salgo con true
                  return true;
               }
            }
            return false;
         }
   }


   public function pointInZone($polygon, $p){
      
    $cnt = 0;
    
    if($p->getX() == "" || $p->getY() == "") return false;
    
    $p1 = $polygon[0];
    for ($i=1;$i<=count($polygon);$i++) {
      $p2 = $polygon[$i % count($polygon)];
      if ($p->getY() > min($p1->getLng(),$p2->getLng())) {
        if ($p->getY() <= max($p1->getLng(),$p2->getLng())) {
    if ($p->getX() <= max($p1->getLat(),$p2->getLat())) {
      if ($p1->getLng() != $p2->getLng()) {
        $xinters = ($p->getY()-$p1->getLng())*($p2->getLat()-$p1->getLat())/($p2->getLng()-$p1->getLng())+$p1->getLat();
        if ($p1->getLat() == $p2->getLat() || $p->getX() <= $xinters)
          $cnt++;
      }
    }
        }
      }
      $p1 = $p2;
    }
  
    if ($cnt % 2 == 0)
      return false;
    else
      return true;
  }


   //realizar el pedido a la tienda
   public function realizarPedidoAction(Request $request){
      $resultado=array("status"=>1,"message"=>'No llega');
      $em = $this->getDoctrine()->getManager();
      $session = $this->getRequest()->getSession();
      if ($session->get("login")){
       try{  
          $sucursalId = $request->get("sucursal");
          $direccionId = $request->get("direccionid"); 
          $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($sucursalId);
          $direccion = $em->getRepository('BackendCustomerAdminBundle:Direccion')->find($direccionId);
          $customer=$em->getRepository('BackendCustomerBundle:Customer')->find($session->get("customer")->getId());
          $paymethod=$em->getRepository('BackendAdminBundle:PayMethod')->findOneByName("Efectivo");
          $pedido= new Pedido();
          $pedido->setCustomer($customer);
          $pedido->setSucursal($sucursal);
          $pedido->setPagado(false); // Nunca va a ir como pagado por ahora
          $pedido->setDireccion($direccion);
          $pedido->setTotal($request->get("total"));
          $pedido->setComentarios($request->get("comentario"));
          $pedido->setPaymethod($paymethod);
          $em->persist($pedido);
          $em->flush();
              //le pongo al pedido como pendiente ni bien lo creo
          $status=$em->getRepository('BackendCustomerAdminBundle:Status')->findOneByName("Pendiente");
          $proceso=new Proceso();
          $proceso->setPedido($pedido);
          $proceso->setStatus($status);
          $proceso->setComentarios("Pedido creado");
          $em->persist($proceso);
          $em->flush();
          
          if ($request->get("productos")){
             $productos = $request->get("productos");
             foreach($productos as $p){
               $detalle = new Detalle();
               if($p["itemId"]){
                   $item = $em->getRepository('BackendCustomerAdminBundle:Item')->find($p["itemId"]);
                   $detalle->setItem($item);
               }else{
                   $producto = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($p["id"]);
                   $detalle->setProducto($producto);
               }
               $detalle->setPedido($pedido);
               $detalle->setCantidad($p["quantity"]);
               $detalle->setPrecio($p["price"]);
               $detalle->setVariedades($p["variedad"]);
               $em->persist($detalle);
               $em->flush();
             }
          
          }
          
          $resultado["status"]=0;
          $resultado["message"]="pedido creado";
          
          $this->sendPedidosEmails($customer, $sucursal);  // pasaba customer y sucursal
          
          
       } catch(Exception $e){
          $resultado["status"]=1;
          $resultado["message"]="no pudo crearse el pedido"; 
       
       }
          
          
      }
      $response = new Response(json_encode($resultado));
        
      $response->headers->set('Content-Type', 'application/json');
  
      return $response;
   
   }

   private function sendPedidosEmails($customer, $sucursal){

       $em = $this->getDoctrine()->getManager(); // me lo reclamaba
       $empresa = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("empresa");
       $email_site = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("email");
       
       $messageCustomer = \Swift_Message::newInstance()
                    ->setSubject("Pedido enviado a"+ $sucursal->getName()+" mediante el sitio "+$empresa->getValue())
                    ->setFrom($email_site->getValue())
                    ->setTo($customer->getEmail())
                    ->setBody(
                        $this->renderView(
                            'FrontendHomeBundle:Cart:pedido_customer_email.html.twig',
                            array('customerName' => $customer->getName(),'sucursalName' => $sucursal->getName(),
                                  'empresa' =>$empresa->getValue(),'sucursalTelefono' =>$sucursal->getPhone() ,'sucursalEmail'=>$sucursal->getEmail() )
                        ),'text/html'
                    );
        
        
         
          @$this->get('mailer')->send($messageCustomer);
          
        $messageSucursal = \Swift_Message::newInstance()
                    ->setSubject("Ha recibido un pedido mediante el sitio ".$empresa->getValue())
                    ->setFrom($email_site->getValue())
                    ->setTo($sucursal->getEmail())
                    ->setBody(
                        $this->renderView(
                            'FrontendHomeBundle:Cart:pedido_sucursal_email.html.twig',
                            array('sucursalName' => $sucursal->getName(),'customerName' => $customer->getName(),
                              'empresa' =>$empresa->getValue())
                        ),'text/html'
                    );
        
        
         
          @$this->get('mailer')->send($messageSucursal);  
   
   }

	
}
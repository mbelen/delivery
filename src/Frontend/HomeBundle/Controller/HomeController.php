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
use Backend\CustomerAdminBundle\Entity\Favorito;
use Backend\CustomerAdminBundle\Entity\Sucursal;
use Backend\CustomerBundle\Entity\Customer;
/**
 * Home controller.
 *
 */
class HomeController extends Controller
{

   
    public function indexAction(Request $request)
    {

        $session = $this->getRequest()->getSession();
        $session->remove('que');
        return $this->render('FrontendHomeBundle:Home:index.html.twig');
    }
    
    public function faqAction(Request $request)
    {    
        return $this->render('FrontendHomeBundle:Home:faqs.html.twig');
                
    }
    
    public function terminosAction(Request $request)
    {    
        return $this->render('FrontendHomeBundle:Home:terminos.html.twig');
                
    }
    
    public function politicasAction(Request $request)
    {    
        return $this->render('FrontendHomeBundle:Home:privacidad.html.twig');
                
    }
	
	
    //obtener listado de zonas y barrios 
    public function menuZonaAction(Request $request)
    {

        $search = trim(mb_convert_case($request->get("q"), MB_CASE_LOWER));

        $listado = array();

        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT b FROM BackendAdminBundle:Barrio b where ";
        $search = mb_convert_case($search, MB_CASE_LOWER);

        if ($search)

            $dql .= "b.name like '%$search%'";

            $dql .= " order by b.name";

            $query = $em->createQuery($dql);

            $resultados = $query->getResult();

      
        foreach ($resultados as $resultado) {

            $barrio = array();

            $barrio['id'] = $resultado->getId();
            $barrio['name'] = $resultado->getName();
            $barrio['zona'] = $resultado->getZona()->getName();
            $barrio['zonaId'] = $resultado->getZona()->getId();
            $listado[] = $barrio;
        }

        $listado[]=array("id"=>-1,"name"=>"Todos","zona"=>"Todas las Zonas","zonaId"=>-1);
        
        $response = new Response(json_encode($listado));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
     

    public function menuCategoriaAction(Request $request){

        $search = trim(mb_convert_case($request->get("q"),MB_CASE_LOWER));

        $listado = array();

        $em = $this->getDoctrine()->getManager();

        $dql="SELECT s FROM BackendAdminBundle:Subcategoria s where " ;
        $search=mb_convert_case($search,MB_CASE_LOWER);

        if ($search)

            $dql.="s.name like '%$search%'";

        $dql.=" order by s.name";

        $query = $em->createQuery($dql);

        $resultados = $query->getResult();

     

            foreach ($resultados as $resultado) {

                $subcategoria = array();

                $subcategoria['id'] = $resultado->getId();
                $subcategoria['name'] = $resultado->getName();
                $subcategoria['category'] = $resultado->getCategoria()->getName();
                $subcategoria['catId'] = $resultado->getCategoria()->getId();
                $subcategoria['restrict'] = $resultado->getCategoria()->getIsRestrict();
                $listado[] = $subcategoria;
            }

      
        $response = new Response(json_encode($listado));

        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }



    //obtener listado de las subtacategorias a partir de la categoria

    private function getSubcategorias($categoriaId){
      $subcategorias = $this->getDoctrine()->getRepository('BackendAdminBundle:Subcategoria')->findBy(array("categoria"=>$categoriaId));
     
      $resultado=array();
      foreach($subcategorias as $s){
            $record=array();
            $record["id"]=$s->getId();
            $record["name"]=$s->getName();
            $record["category"] = $s->getCategoria()->getName();
            $resultado[] = $record;
       }
       
       return $resultado;
    }
    
    public function getQueAction(Request $request){
         $q=$request->get("q");
         //busco posibles valores de nombre tiendas
         //categorias y subcategorias
         $em = $this->getDoctrine()->getManager();
         $sql="select s from BackendCustomerAdminBundle:Sucursal s where s.name like '%".$q."%' order by s.name";
         $query = $em->createQuery($sql)->setMaxResults(3);
         
         $resultados = $query->getResult();
         
         $listado=array();
         
         foreach($resultados as $r){
           $listado[]=array("name"=>$r->getName());
         }
         
         /* $sql="select c from BackendAdminBundle:Categoria c where c.name like '%".$q."%' order by c.name";
         $query = $em->createQuery($sql)->setMaxResults(3);
         
         $resultados = $query->getResult();
         
         foreach($resultados as $r){
           $listado[]=array("name"=>$r->getName());
         } */
         
         $sql="select s from BackendAdminBundle:Subcategoria s where s.name like '%".$q."%' order by s.name";
         $query = $em->createQuery($sql)->setMaxResults(3);
         
         $resultados = $query->getResult();
         
         foreach($resultados as $r){
           $listado[]=array("name"=>$r->getName());
         }
        
         $response = new Response(json_encode($listado));

        $response->headers->set('Content-Type', 'application/json');

        return $response;

    
    }


    private function getThumb($pathImage, $filter='logo_thumb'){

      $imagemanagerResponse = $this->container
            ->get('liip_imagine.controller')
            ->filterAction(
                new Request(),         // http request
                $pathImage,      // original image you want to apply a filter to
                $filter              // filter defined in config.yml
            );

        // string to put directly in the "src" of the tag <img>
        $cacheManager = $this->container->get('liip_imagine.cache.manager');
        $srcPath = $cacheManager->getBrowserPath($pathImage, $filter);
        return $srcPath;

    }

    // devuelve las tiendas según barrio y subcategoria devolver con status
    //si selecciono un barrio elijo en ese barrio
    //si selecciono solo que me fijo si esta geolocalizado y sino busco en general
    //si selecciono barrio y que me fijo tiendas en ese barrio que eligio
    public function getTiendasAction(Request $request){
        
        $barrioId =trim(mb_convert_case($request->get("barrio"),MB_CASE_LOWER));
        $zonaId =trim(mb_convert_case($request->get("zona"),MB_CASE_LOWER));
        $time = trim(mb_convert_case($request->get("time"),MB_CASE_LOWER));
        $dia = trim(mb_convert_case($request->get("day"),MB_CASE_LOWER));
        
        $que = trim(mb_convert_case($request->get("que"),MB_CASE_LOWER));
        
     
        
        $limit = 3;
        $offset = $request->get("offset");
        
        $time_array = explode(":",$time);
        $ahora = $time_array[0]*60 + $time_array[1];
        
        $where='';
        
        $select="SELECT u ";
        $dql= " FROM BackendCustomerAdminBundle:Sucursal u 
               JOIN u.customer c JOIN c.status e "; 

        //validar que esten activas las tiendas que los usuarios esten activos y habilitados 
        $where .=" where u.is_active= 1 and c.isActive = true and e.name = 'Habilitado'";
        $session = $this->getRequest()->getSession();
        
        if ( ($zonaId && $zonaId != -1 ) || ($barrioId && $barrioId != -1)){
              $dql .=" JOIN u.direccion d ";
        }
        
        if ($zonaId && $zonaId != -1){
            
            $where.= " and d.zona = ".$zonaId;
            
            $session->set('zonaId',$zonaId);
            $session->save();
        }
        
        
        if ($barrioId && $barrioId != -1){
            
            $where.= " and d.barrio = ".$barrioId;
            
            $session->set('barrioId',$barrioId);
            $session->save();
        }


        if($que){

            $dql.= " JOIN u.subcategorias s  "; 

          
            $session->set('que',$que);
            $session->save();
            $where .=" and ( s.name like '%".$que."%' or u.name like '%".$que."%')";
        }
        

        $dql .=$where." order by u.id";
        $em = $this->getDoctrine()->getManager();
        $sql="select count(u.id)".$dql;
        $total=$em->createQuery($sql)
                ->getSingleScalarResult();
       
       
          
        $sql=$select.$dql; 
            
        
    		$query = $em->createQuery($sql)->setMaxResults($limit)->setFirstResult($offset);
    		$tiendas = $query->getResult();
        
        
        $offset += $limit;
       if ($offset > $total){
           $offset = -1; // no hay más para la busqueda
           
       }
        
        
        $listado=array();
        

        foreach ($tiendas as $tienda) {
			         
      			  $open = false;
      			  $horarios = $tienda->getHorarios();
      			 
                    $horarios_tienda = $this->hoyHorario($horarios,$dia,$time);
               
                    $open = $this->checkOpenNow($horarios,$dia,$time);
      			  $cierra = null; // para validar si está abierto al momento de comprar. 	
      			  $record=array();
                    $record["id"]=$tienda->getId();
                    $record["name"]=$tienda->getName();              
      			  if($tienda->getWebPath()){
                     
                        $record["imagen"]=$this->getThumb($tienda->getWebPath());
    		  	  }else{		  	  	
      				    $record["imagen"]="images/home/shop_default.png";
    		  	  }
                    $record["horario"] = $horarios_tienda; 
                    
                    if($open){				  
      				  $record["promo"] = "images/home/tienda_open.png";
      				  $record["title"] = "Abierto";
      			 
      			  }else{
      				  
      				  if($tienda->getOpen()){
      					  $record["promo"] = "images/home/tienda_pedido.png";
      					  $record["title"] = "Toma pedidos"; 					   
      				  
      				  }else{
      					  $record["promo"] = "images/home/tienda_close.png";
      					  $record["title"] = "Cerrado";					  
      				  }				  
      			  }
      			  $record["dia"] = $dia;
      
      			  $record["cierra"] = $cierra;
      			  $record["link"] = $this->generateUrl('frontend_show_products', array('id' =>$tienda->getId()));
              $record["favorito"]=$this->isFavorito($tienda);
              $record["restricted"] =$this->checkSucursalIsResctricted($tienda->getSubcategorias());
              $listado[] = $record;

       
	     	} 
		   
       $resultado=array("listado"=>$listado,"zona"=>$zonaId,"barrio"=>$barrioId,"q"=>$que,"offset"=>$offset);
    
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    
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
    
    private function generateHorarios($horarios){
       $horarios_tienda = array();
       foreach($horarios as $horario) {

                  if ($horario->getCerrado()) {
                      $horarios_tienda[] = $horario->getDia()->getName() . ": Cerrado";
                  }elseif( $horario->getOpenAll() ){ 
                      $horarios_tienda[] = $horario->getDia()->getName() . ": 24hs"; 
                  }else {

                      $hours = $horario->getDia()->getName() . ":" . $horario->getDesde() . "-" . $horario->getHasta() . " hs.";

                      if($horario->getHorarioPartido() ){

                          $hours .=  "<br>" .$horario->getDesdeT() . "-" . $horario->getHastaT() . " hs.";
                      }
                      
                      $horarios_tienda[] = $hours;
                  }
              }
       return $horarios_tienda;
    }

    private function hoyHorario($horarios,$dia,$time){

        $time_array = explode(":", $time);
        $hora = $time_array[0] * 60 + $time_array[1];

        $horarios_tienda = array();

        foreach($horarios as $horario) {

            if($hora > 360) {

                if($horario->getDia()->getNro() == $dia) {

                    if ($horario->getCerrado()) {
                        $horarios_tienda[] =  "Hoy descansamos";
                    } elseif ($horario->getOpenAll()) {
                        $horarios_tienda[] = "Abierto las 24hs";
                    } else {

                        $hours = "Abrimos: ".$horario->getDesde() . "hs. Cerramos: " . $horario->getHasta()." hs.";
                        $horarios_tienda[] = $hours;

                        if ($horario->getHorarioPartido()) {

                            $hours_hasta = "Abrimos: ".$horario->getDesdeT() . "hs. Cerramos: " . $horario->getHastaT()." hs.";

                            $horarios_tienda[] = $hours_hasta;
                        }
                    }
                }
            }else{

                if($dia == 0) {$d = 6;} else {$d = $dia -1;}

                if($horario->getDia()->getNro() == $d) {

                    if ($horario->getCerrado()) {
                        $horarios_tienda['close'] = $horario->getDia()->getName() . ": Cerrado";
                    } elseif ($horario->getOpenAll()) {
                        $horarios_tienda['open'] = $horario->getDia()->getName() . ": 24hs";
                    } else {

                        $hours = $horario->getDesde() . "-" . $horario->getHasta();
                        $horarios_tienda[] = $hours;

                        if ($horario->getHorarioPartido()) {

                            $hours_hasta = $horario->getDesdeT() . "-" . $horario->getHastaT();

                            $horarios_tienda[] = $hours_hasta;
                        }
                    }
                }

            }
        }
        return $horarios_tienda;

    }

    public function getTiendasPremiumAction(Request $request){

        $time = trim(mb_convert_case($request->get("time"),MB_CASE_LOWER));
        $dia = trim(mb_convert_case($request->get("day"),MB_CASE_LOWER));
        $dql= "SELECT u FROM BackendCustomerAdminBundle:Sucursal u JOIN u.customer c JOIN c.status e"; 
        
         //validar que esten activas las sucursales que sean premium 
         // que esten activos los usuarios y que esten validados sus datos
        $dql .=" where u.is_active= true and u.premium= true and c.isActive = true and e.name = 'Habilitado' ";  
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        $tiendas = $query->getResult();


        $listado=array();
        
        foreach ($tiendas as $tienda) {
			  
			  $open = false;
			  $horarios = $tienda->getHorarios();
              $open = $this->checkOpenNow($horarios,$dia,$time);
			  //$horarios_tienda = $this->generateHorarios($horarios);
              $horarios_tienda = $this->hoyHorario($horarios,$dia,$time);

			  $record=array();
              $record["id"]=$tienda->getId();
              $record["name"]=$tienda->getName();
			  if($tienda->getWebPath()){
              	$record["imagen"]=$tienda->getWebPath();
		  	  }else{		  //imagen default si no tiene una asociada	  	
				$record["imagen"]="images/home/shop_default.png";
		  	  }
              $record["horario"] = $horarios_tienda; 
              
              if($open){				  
				  $record["promo"] = "images/home/tienda_open.png";
				  $record["title"] = "Abierto";
			  }else{
				  
				  if($tienda->getOpen()){
					  $record["promo"] = "images/home/tienda_pedido.png";
					  $record["title"] = "Toma pedidos";
				  }else{
					  $record["promo"] = "images/home/tienda_close.png";
					  $record["title"] = "Cerrado";					  
				  }				  
			  }
			  
         $record["favorito"]=$this->isFavorito($tienda);
         $record["link"] =$this->generateUrl('frontend_show_products', array('id' => $tienda->getId()));
         $record["restricted"] =$this->checkSucursalIsResctricted($tienda->getSubcategorias());
         $listado[] = $record;

	   }
       $response = new Response(json_encode($listado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    
    }
    
    private function isFavorito($tienda){
       $session = $this->getRequest()->getSession();
       $customer=$session->get("customer");
       if (!$customer){
         return false;
       }
       else{
        $customer_id=$customer->getId();
        $sucursal_id=$tienda->getId();
        $em = $this->getDoctrine()->getManager();
        $favorito = $em->getRepository('BackendCustomerAdminBundle:Favorito')->findOneBy(array("customer"=>$customer_id,"sucursal"=>$sucursal_id));
        if ($favorito){
          return true;
        }else{
          return false;
        }  
       
       }
    
    }

    /* Devuelve el header de la tienda
    *  en un futuro permitirá asignar header personalizado
    */

    /*
    public function getHeaderTienda(Request $request){

        $id = trim(mb_convert_case($request->get("tienda"),MB_CASE_LOWER));

        $em = $this->getDoctrine()->getManager();
        $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);

        $header = $sucursal->getHeaderPath();

        $data['header'] = $header;

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
*/
    public function resetFiltroAction(Request $request,$id){

        $session = $this->getRequest()->getSession();
        $session->remove('categoria');

        return $this->getProductsByTiendaAction($request,$id);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Response
     */

    public function getProductsByTiendaAction(Request $request, $id){

        $search = " ";
        $session = $this->getRequest()->getSession();
        $time = "00:00";//trim(mb_convert_case($request->get("time"),MB_CASE_LOWER));
        //$dia = 1; //trim(mb_convert_case($request->get("day"),MB_CASE_LOWER));
        $subId = $session->get('categoria');
        $filter = trim(mb_convert_case($request->get("filter"),MB_CASE_LOWER));
        $resultado = array();
        $promo_data = array();
        $r_promo = array();
        $today= new \DateTime("today");

		    if($id) {
            $em = $this->getDoctrine()->getManager();
            $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);
            $productos = $sucursal->getProductos();
            $horarios = $sucursal-> getHorarios();
            $promos = $sucursal->getPromociones();


            foreach($promos as $promo){

                if($promo->getStatus() == 1 && $today >= $promo->getDesde() && $today<= $promo->getHasta() ) {

                    //if($this->checkPromoNow($promo->getHorarios(), 1, '00:00') == true) {

                        $type = $promo->getType();
                        
                        $r_promo['promo'] = $promo;

                        if($promo->getProductos() != null) { // promo de producto
                            
                            $r_promo['items_productos'] = $productos;
                            
                            $promo_data[] = $r_promo;
                            
                        }else{ // promo de categoria
                            /*
                            if($type == 1){
                                $r_promo['valor'] = $promo->getDetail();

                            }else{

                                $valores = explode("x", $promo->getDetail());
                                $r_promo['valor'] = $valores;
                            }

                            $r_promo['promo'] = $promo;
                            $r_promo['producto'] = 0;
                            $resultado_promos[] = $r_promo;
                            */
                            
                        }
                    //}
                }
            }

            if ($subId) {

                $subcategoria = $em->getRepository('BackendAdminBundle:Subcategoria')->find($subId)->getName();

                foreach ($productos as $prod) {

                    if ($prod->getSubcategoria()->getId() == $subId && $prod->getIsActive() == true) {

                        $resultado[] = $prod;
                    }
                }

            }else{

                foreach ($productos as $prod) {

                    if($prod->getIsActive() == true){

                        $resultado[] = $prod;
                    }
                }

                $subcategoria = "Todos";
            }

            $count = count($resultado);

            $regiones=array();

            foreach($sucursal->getRegiones() as $region){
              $separador='';
              $poligono='';
              foreach($region->getCoordenadas() as $c){
                $poligono .=$separador." new google.maps.LatLng(".$c->getLat().", ".$c->getLng().") "; 
                $separador=",";
              }
              $regiones[]=$poligono;
            }


            $restricted = $this->checkSucursalIsResctricted($sucursal->getSubcategorias());
            return $this->render('FrontendHomeBundle:Shop:index.html.twig', array(
                'restricted'=>$restricted,
                'tienda' => $sucursal,
                'productos' => $resultado,
                'subcategoria' => $subcategoria,
                'count' => $count,
                'search'=>$search,
                'horarios'=>$horarios,
                'promos' =>$promo_data,
                'status' =>1,
                'regiones'=>$regiones
            ));

		}else{
			
			return $this->render('FrontendHomeBundle:Home:index.html.twig');
		}
	}



    /**
     * @param Request $request
     * @return Response
     */
    
    public function addFavoritoAction(Request $request){
      $session = $this->getRequest()->getSession();
      $resultado=array("status"=>1,"message"=>'');
      $customer=$session->get("customer");
      if ($customer){
          $sucursal_id = $request->request->get("sucursal");
          $customer_id = $customer->getId();    
                    
          $em = $this->getDoctrine()->getManager();
    
          $favorito = $em->getRepository('BackendCustomerAdminBundle:Favorito')->findOneBy(array("customer"=>$customer_id,"sucursal"=>$sucursal_id));
           if ($favorito){
              $em->remove($favorito);
              $em->flush();
              $resultado["status"]=1;
              $resultado["message"]="Se quito el favorito";
              
           }else{
              $favorito = new Favorito();
              $favorito->setCustomer($em->getRepository('BackendCustomerBundle:Customer')->find($customer_id));
              $favorito->setSucursal($em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($sucursal_id));
              $em->persist($favorito);
              $em->flush();
              $resultado["status"]=0;
              $resultado["message"]="Agregado como favorito";
           }
       }else{
          $resultado["message"]="Debe logearse para agregar la sucursal como favorita";
       }
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
      
    
    }

    private function checkPromoNow($horarios,$dia,$time){

        $time_array = explode(":",$time);
        $ahora = $time_array[0] * 60 + $time_array[1];

        foreach ($horarios as $horario) {

            if ($horario->getDia()->getNro() == $dia) {

                if($horario->getHasta() > $ahora && $ahora > $horario->getDesde()){

                    return true;

                }else if($ahora < $horario->getDesde()){ // si es del día pero aun no empezó

                    return true;
                }
            }

        }
        return false;
    }


    private function checkOpenNow($horarios,$dia,$time)
    {

        //$dia = 1;
        //$time = "0:30";

        $time_array = explode(":", $time);
        $ahora = $time_array[0] * 60 + $time_array[1];

        foreach ($horarios as $horario) {

            if($ahora > 360 && $ahora < 1439) { // horario diurno

                if ($horario->getDia()->getNro() == $dia) {

                    if ($horario->getCerrado() == 1) {

                        return false; //salgo directo con false porque esta cerrado

                    } elseif ($horario->getOpenAll() == 1) {

                        return true; //salgo directo esta abierto porque abre 24 hs ese dia

                    } else {

                        if ($horario->getDesde()) {

                            $desde_array = explode(":", $horario->getDesde());
                            $desde = $desde_array[0] * 60 + $desde_array[1];
                        }
                        if ($horario->getHasta()) {
                            $hasta_array = explode(":", $horario->getHasta());
                            $hasta = $hasta_array[0] * 60 + $hasta_array[1];
                        }
                        if ($horario->getDesdeT()) {

                            $desdeT_array = explode(":", $horario->getDesdeT());
                            $desdeT = $desdeT_array[0] * 60 + $desdeT_array[1];
                            $h_partido = true;
                        }
                        if ($horario->getHastaT()) {

                            $hastaT_array = explode(":", $horario->getHastaT());
                            $hastaT = $hastaT_array[0] * 60 + $hastaT_array[1];
                            $h_partido = true;
                        }

                        if ($horario->getHorarioPartido()) {


                            if ($ahora >= $desde && $ahora < $hasta) { // horario de la mañana

                                return true;
                            }
                            if ($hastaT < $desdeT) { // cierra despues de las 0:00

                                if ($ahora >= $desdeT) {

                                    return true;

                                } else {
                                    return false;
                                }

                            } else {

                                if ($ahora >= $desdeT && $ahora < $hastaT) {

                                    return true;

                                } else {
                                    return false;
                                }
                            }

                        } else { // no es horario partido

                            if ($desde < $hasta) {  // ejemplo de 9:00 a 21:00

                                if ($ahora >= $desde && $ahora < $hasta) {

                                    return true;
                                } else {

                                    return false;
                                }
                            } else { // ejemplo 9:00 a 0:30

                                if ($ahora >= $desde) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        } // no es partido
                    } // else numero
                 }
                }
                if ($ahora < 360) {

                    if ($dia == 0) {
                        $d = 6;
                    } else {
                        $d = $dia - 1;
                    }

                    if ($horario->getDia()->getNro() == $d) {

                        if ($horario->getCerrado() == 1) {

                            return false; //salgo directo con false porque esta cerrado

                        } elseif ($horario->getOpenAll() == 1) {

                            return true; //salgo directo esta abierto porque abre 24 hs ese dia

                        } else {

                            if ($horario->getDesde()) {

                                $desde_array = explode(":", $horario->getDesde());
                                $desde = $desde_array[0] * 60 + $desde_array[1];
                            }
                            if ($horario->getHasta()) {
                                $hasta_array = explode(":", $horario->getHasta());
                                $hasta = $hasta_array[0] * 60 + $hasta_array[1];
                            }
                            if ($horario->getDesdeT()) {

                                $desdeT_array = explode(":", $horario->getDesdeT());
                                $desdeT = $desdeT_array[0] * 60 + $desdeT_array[1];
                                $h_partido = true;
                            }
                            if ($horario->getHastaT()) {

                                $hastaT_array = explode(":", $horario->getHastaT());
                                $hastaT = $hastaT_array[0] * 60 + $hastaT_array[1];
                                $h_partido = true;
                            }

                            if ($horario->getHorarioPartido()) {

                                if ($ahora < $hastaT) { // valido que sea menor que la hora de cierre

                                    return true;

                                } else {

                                    return false;
                                }
                            } else {

                                if ($ahora < $hasta && $ahora >= $desde) {

                                    return true;

                                } else {

                                    return false;
                                }
                            }

                        } // horario definido
                    } // valida dia

                } // horario nocturno

            } // foreach
    } // function




    /* Verifica si la tienda sigue abierta */
	
	public function checkTimeAction(Request $request){
		
		$dia = trim(mb_convert_case($request->get("day"),MB_CASE_LOWER));
		$hora = trim(mb_convert_case($request->get("time"),MB_CASE_LOWER));
		$tiendaId = trim(mb_convert_case($request->get("tienda"),MB_CASE_LOWER));
        
        $em = $this->getDoctrine()->getManager();

        $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($tiendaId);
		
		$horarios = $sucursal->getHorarios();
		 
		$status = $this->checkOpenNow($horarios,$dia,$hora);
		
		$resultado=array("status"=>$status);
      
        $response = new Response(json_encode($resultado));
        
        $response->headers->set('Content-Type', 'application/json');
  
        return $response;
		
	}

    /**
     * @param Request $request
     * @return Response
     */

    public function getPromosVigentesAction(Request $request){

        // TODO validar el tema de stock
        $hoy = new \DateTime($request->get("today"));
      
        
        $resultados = array();

        $em = $this->getDoctrine()->getManager();

        $banners = $em->getRepository('BackendCustomerAdminBundle:Banner')->findAll();

        foreach ($banners as $b){
        	
            if(( $hoy >= $b->getDesde() && $hoy<= $b->getHasta()) && $b->getIsActive() == true){
                $r = array();
                $r['img'] = $b->getWebPath();
                $r['tiendaId'] = $b->getSucursal()->getId();
                $r['link']=$this->generateUrl('frontend_show_products', array('id' =>$b->getSucursal()->getId()));
                $resultados[] = $r;
            }
        }

        $response = new Response(json_encode(array("banners"=>$resultados)));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */

    public function checkBarrioAction(Request $request){
     
      $barrio=trim(mb_convert_case($request->get("barrio"),MB_CASE_LOWER));
      $zona=trim(mb_convert_case($request->get("zona"),MB_CASE_LOWER));
      
      $dql="SELECT u FROM BackendAdminBundle:Zona u "  ;
      $dql.=" where u.name like '".$zona."' ";
      $dql .=" order by u.name";
      
      //primero localizo la zona luego localizo el barrio
      //si no existe la zona ni el barrio muestro todo
      //sino muestro segun lo que pueda identificar
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery($dql);
       $zonas = $query->getResult();
       $zonaId = 0;
       $barrioId = 0;
       if (count($zonas) == 1){
          $zonaId = $zonas[0]->getId();
          $dql="SELECT u FROM BackendAdminBundle:Barrio u "  ;
          $dql.=" where u.name like '".$barrio."' and u.zona ='".$zonaId."' ";
          $dql .=" order by u.name";
          
          $query = $em->createQuery($dql);
          $barrios = $query->getResult();
          if (count($barrios) == 1){
              $barrioId = $barrios[0]->getId();
          }
       }else{
		   
		  $dql="SELECT u FROM BackendAdminBundle:Barrio u "  ;
          $dql.=" where u.name like '".$barrio."' order by u.name";
		  $query = $em->createQuery($dql);
		  $barrios = $query->getResult();
          if (count($barrios) == 1){
              $barrioId = $barrios[0]->getId();
              $zonaId = $barrios[0]->getZona()->getId();
          }		  	
	   }
	   
       $resultado=array("zonaId"=>$zonaId, "barrioId"=>$barrioId);
      
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
       
    
    }
   
    
	  public function forgotPasswordAction(Request $request){
       	$resultado=array("status"=>1,"message"=>'');    //status => 0 no hay problemas 1: error
    		$email=$request->get("email");
        if ($email){
            $service = new \Backend\CustomerBundle\Services\CustomerService($this->get('doctrine.orm.default_entity_manager'));
            $forgotResponse = $service->forgotPassword($email);
            $respuesta=json_decode($forgotResponse); 
         
            if ($respuesta->status == 0) //se creo el cliente envio mail
        		{
        		  $em = $this->getDoctrine()->getManager();
              $empresa = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("empresa");
        		  $email_site = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("email");
        		  
        		  $url= $this->generateUrl(
              'frontend_change_pass',
              array('codigo' =>$respuesta->codigo ), true );
        
              $message = \Swift_Message::newInstance()
                    ->setSubject("Olvido su Contraseña en el sitio ".$empresa->getValue())
                    ->setFrom($email_site->getValue())
                    ->setTo($respuesta->email)
                    ->setBody(
                        $this->renderView(
                            'BackendCustomerBundle:Customer:forgot_email.html.twig',
                            array('name' => $respuesta->name,
                                   'url' => $url  )
                        ),'text/html'
                    );
            
            
             
              @$this->get('mailer')->send($message);
               $resultado["status"]=0;
               $resultado["message"]=$respuesta->message;
               
        		}else{
               $resultado["message"]=$respuesta->message;
             }
        		
           
        }
        $response = new Response(json_encode($resultado));
        
        $response->headers->set('Content-Type', 'application/json');
  
        return $response;
    }
	

    public function registerAction(Request $request)
    {
       	$resultado=array("status"=>1,"message"=>'');
        $em = $this->getDoctrine()->getManager();
       if ($request->getMethod() == 'POST') {  
        
        $customer=array();
        $customer["email"] = $request->get("email");    
      	$customer["password"] = $request->get("password");
    	  $customer["name"] = $request->get("name");
        $customer["lastname"] = $request->get("lastname");
		
    		if($request->get("comercio") == 1){
            	$customer["role"]="ROLE_COMERCIO"; //el comercio se da de alta como pendiente
              $customer["isComercio"] = true;
              $status=$em->getRepository('BackendCustomerBundle:Status')->findOneByName("Pendiente");
              $customer["status"]=$status;
         
    		}else{
    			$customer["role"]="ROLE_CLIENTE";  //el usuario se da de alta como habilitado
  				$customer["isComercio"] = false;
  				$status=$em->getRepository('BackendCustomerBundle:Status')->findOneByName("Pendiente");
  				$customer["status"]=$status;			
        }        
        
    		$service = new \Backend\CustomerBundle\Services\CustomerService($this->get('doctrine.orm.default_entity_manager'));
    		$registerResponse = $service->register($customer);
    		
    		$respuesta=json_decode($registerResponse);
    		
    		if ($respuesta->status == 0) //se creo el cliente envio mail
    		{
    		  
			  $empresa = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("empresa");
    		  $email_site = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("email");
    		  
    		  $url= $this->generateUrl(
            'frontend_activate_account',
            array('codigo' =>$respuesta->codigo ), true );
    		  
    		  $message = \Swift_Message::newInstance()
                    ->setSubject("Registro en ".$empresa->getValue())
                    ->setFrom($email_site->getValue())
                    ->setTo($customer["email"])
                    ->setBody(
                        $this->renderView(
                            'BackendCustomerBundle:Customer:register_email.html.twig',
                            array('name' => $customer["name"],
                             'url' =>$url
                             )
                        ),'text/html'
                    );
        
        
         
          @$this->get('mailer')->send($message);
          $resultado["status"]=0;
          $resultado["message"]=$respuesta->message;
    		}else{
           $resultado["message"]=$respuesta->message;
         }
    		
        }
       
       $response = new Response(json_encode($resultado));
        
        $response->headers->set('Content-Type', 'application/json');
  
        return $response;   
        
        
    }
    
    
    public function changePasswordAction(Request $request, $codigo){
     
       
    if ($request->getMethod() == 'POST') {
       if ($codigo != '')
       {
       	$cambio=array("codigo"=>$request->get("codigo"),
                      "password"=>$request->get("password")
         );
         
        $service = new \Backend\CustomerBundle\Services\CustomerService($this->get('doctrine.orm.default_entity_manager'));
      	$response = $service->changePassword($cambio);
    		
      	$respuesta=json_decode($response);
      
      	if ($respuesta->status == 0) //se cambio la contraseña
    		{
    		  $em = $this->getDoctrine()->getManager();
          	  $empresa = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("empresa");
    		  $email_site = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("email");
    		  
          $message = \Swift_Message::newInstance()
                    ->setSubject("Cambio de Contraseña para el sitio ".$empresa->getValue())
                    ->setFrom($email_site->getValue())
                    ->setTo($respuesta->email)
                    ->setBody(
                        $this->renderView(
                            'BackendCustomerBundle:Customer:changepass_email.html.twig',
                            array('name' => $respuesta->name,
                                   'email' => $respuesta->email,
                                    'password' => $respuesta->password )
                        ),'text/html'
                    );
        
        
         
          @$this->get('mailer')->send($message);
         
          $this->get('session')->getFlashBag()->add('success' , 'Se ha enviado un mail con los datos de su cuenta.');
         
    		}
    	else{
          
           
             $this->get('session')->getFlashBag()->add('error' , 'Link incorrecto o ya ha activado su cuenta');
      }	
    
       }
       else{
            $this->get('session')->getFlashBag()->add('error' , 'Link incorrecto.');
          
       }
    
    
    }
       
          
     return $this->render('FrontendHomeBundle:Home:changePass.html.twig',array('codigo'=>$codigo));  
       
    
    }
    
    
    public function activateAccountAction(Request $request, $codigo){
    
     
   
       if ($codigo != '')
       {
       	$codigo=$this->getRequest()->get("codigo", null);
         
        $service = new \Backend\CustomerBundle\Services\CustomerService($this->get('doctrine.orm.default_entity_manager'));
      	$response = $service->activateAccount($codigo);
    		
      	$respuesta=json_decode($response);
      
      	if ($respuesta->status == 0) //se activo la cuenta
    		{
    		  $em = $this->getDoctrine()->getManager();
          $empresa = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("empresa");
    		  $email_site = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("email");
    		  
          $message = \Swift_Message::newInstance()
                    ->setSubject("Activo su cuenta para el sitio ".$empresa->getValue())
                    ->setFrom($email_site->getValue())
                    ->setTo($respuesta->email)
                    ->setBody(
                        $this->renderView(
                            'BackendCustomerBundle:Customer:activar_email.html.twig',
                            array('name' => $respuesta->name)
                        ),'text/html'
                    );
        
        
         
          @$this->get('mailer')->send($message);
          
           $this->get('session')->getFlashBag()->add('success' , 'Se ha activado su cuenta correctamente.');    
    		}
    	else{
          $this->get('session')->getFlashBag()->add('error' , 'No se ha podido activar la cuenta.');
      }	
    
       }
       else{
       
       $this->get('session')->getFlashBag()->add('error' , 'Link incorrecto.');
       }
    
    
    
         return $this->render('FrontendHomeBundle:Home:activate_account.html.twig', array('codigo'=>$codigo));
       
       
    
    }
    
    
    public function isLoginAction(Request $request){
        $session = $this->getRequest()->getSession();
        $resultado=array("status"=>1,"message"=>'');
         if ( $session->get('login') ){
            $resultado["status"]=0;
            $resultado["message"]="Esta logeado";
         }else{
            $resultado["status"]=1;
            $resultado["message"]="No esta logeado";
         }
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    }
    
		
    
} 
 

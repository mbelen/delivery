<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Sucursal;
use Backend\AdminBundle\Entity\Horario;
use Backend\CustomerAdminBundle\Form\SucursalType;

use Backend\CustomerAdminBundle\Entity\Customer;
/**
 * Sucursal controller.
 *
 */
class SucursalController extends Controller
{

     public function generateSQL($search){
     
        $user=$this->getUser();
	      
        $dql="SELECT u FROM BackendCustomerAdminBundle:Sucursal u JOIN u.customer c where c.id = ".$user->getId()  ;

		$search= mb_convert_case($search,MB_CASE_LOWER);        
       
        if ($search)
          
		  $dql.=" and u.name like '%$search%' ";
          
          $dql .=" order by u.id"; 
        
        return $dql;
     
     }

    /**
     * Lists all Direcciones entities.
     *
     */
    public function indexAction(Request $request,$search){
		
        if ( $this->get('security.context')->isGranted('ROLE_VIEWSUCURSAL')) {
         $em = $this->getDoctrine()->getManager();
        
         $dql=$this->generateSQL($search);
         $query = $em->createQuery($dql);
        
         $paginator  = $this->get('knp_paginator');
         $pagination = $paginator->paginate(
         $query,
         $this->get('request')->query->get('page', 1)/*page number*/,
         $this->container->getParameter('max_on_listepage')/*limit per page*/
     		);
        
         $deleteForm = $this->createDeleteForm(0);
         return $this->render('BackendCustomerAdminBundle:Sucursal:index.html.twig', 
         array('pagination' => $pagination,
         'delete_form' => $deleteForm->createView(),
         'search'=>$search
         ));
        
     	}
      else
          throw new AccessDeniedException(); 
     }
		
		
	
	
    /**
     * Creates a new Direccion entity.
     *
     */
     
     
     
    public function createAction(Request $request)
    {   
        if ( $this->get('security.context')->isGranted('ROLE_ADDSUCURSAL')) {
        
			$entity  = new Sucursal();
			$customerId = $this->getUser()->getId();
			$em = $this->getDoctrine()->getManager();
			$customer = $em->getRepository('BackendCustomerBundle:Customer')->find($customerId);

			$form = $this->createForm(new SucursalType(), $entity, array("customerId"=>$customerId));
			$form->bind($request);
			$dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();   
      
			if ($form->isValid()) {
            
            // categorias no se agregan ya que se pueden obtener de las subcategorias 
            /*if ($request->get("categorias")){ 
             $categorias=explode(",",$request->get("categorias"));
             foreach($categorias as $id){
                 $cat = $em->getRepository('BackendAdminBundle:Categoria')->find($id);
                 $entity->addCategoria($cat);
              }
            } */
            
            //add subcategorias
            if ($request->get("subcategoria")){
             $subcategorias=$request->get("subcategoria");//explode(",",$request->get("subcategorias"));
             foreach($subcategorias as $k=>$id){
            
                 $sub = $em->getRepository('BackendAdminBundle:Subcategoria')->find($id);
                 $entity->addSubcategoria($sub);
              }
            }  
             //add horarios
             $fromH=$request->get("fromH");
             $fromM=$request->get("fromM");
             $toH=$request->get("toH");
             $toM=$request->get("toM");
            $fromHT=$request->get("fromHT");
            $fromMT=$request->get("fromMT");
            $toHT=$request->get("toHT");
            $toMT=$request->get("toMT");
             $closed=$request->get("closed");
             $abierto=$request->get("abierto");
             $partido=$request->get("partido");
             foreach($dias as $d){
                $horario = new Horario();
                $horario->setDia($d);
                //esta cerrado
                if (isset($closed[$d->getId()]) &&  $closed[$d->getId()] == 1){
                       $horario->setCerrado(true);
                }elseif(isset($abierto[$d->getId()]) &&  $abierto[$d->getId()] == 1){
                        $horario->setOpenAll(true);
                }else{    
                       if(isset($partido[$d->getId()]) &&  $partido[$d->getId()] == 1){
                           $horario->setHorarioPartido(true);                 
                       }
                       $horario->setCerrado(false);
                       $horario->setOpenAll(false);
                       $horario->setDesde($fromH[$d->getId()].$fromM[$d->getId()]);
                       $horario->setHasta($toH[$d->getId()].$toM[$d->getId()]);
                       $horario->setDesdeT($fromHT[$d->getId()].$fromMT[$d->getId()]);
                       $horario->setHastaT($toHT[$d->getId()].$toMT[$d->getId()]);
                }
                $em->persist($horario);
                $em->flush();
                $entity->addHorario($horario);
             }
                $entity->setCustomer($customer);
			      $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se ha agregado una nueva sucursal.');
            return $this->redirect($this->generateUrl('sucursal_edit', array('id' => $entity->getId())));
        }
        
        $em = $this->getDoctrine()->getManager();
        

        return $this->render('BackendCustomerAdminBundle:Sucursal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'dias' => $dias

        ));
      }
      else
       throw new AccessDeniedException(); 
    }

    /**
    * Creates a form to create a Cliente entity.
    *
    * @param Direccion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Sucursal $entity)
    {
        $customerId = $this->getUser()->getId();
        /*
        $form = $this->createForm(new SucursalType(), $entity,array(
            'foo' => 'baz',
            'action' => $this->generateUrl('sucursal_create'),
            'method' => 'POST',
        ));
        */ 
		$form = $this->createForm(new SucursalType(), $entity, array('user' => 1));
        
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Barrio entity.
     *
     */
    public function newAction()
    {
       if ( $this->get('security.context')->isGranted('ROLE_ADDSUCURSAL')) {
        $entity = new Sucursal();
        $customerId=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();
        $form   = $this->createForm(new SucursalType(), $entity, array("customerId"=>$customerId));


        return $this->render('BackendCustomerAdminBundle:Sucursal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'dias' => $dias

            
        ));
       }
       else
          throw new AccessDeniedException();
    }

  
    /**
     * Displays a form to edit an existing Barrio entity.
     *
     */
    public function editAction($id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODSUCURSAL')) { 
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);

        if (!$entity) {
            
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la direccion.');
             return $this->redirect($this->generateUrl('sucursal'));
        }
        $customerId = $this->getUser()->getId();
        $editForm = $this->createForm(new SucursalType(), $entity,array("customerId"=>$customerId) );
        $deleteForm = $this->createDeleteForm($id);
        $dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();
        $horarios=array();
        foreach($entity->getHorarios() as $h){
             
              $id=$h->getDia()->getId();
              if ($h->getDesde()){
                $desde=explode(":",$h->getDesde());
              }else{
                $desde=array(0,0);
              }
              if ($h->getHasta()){
                $hasta=explode(":",$h->getHasta()); 
              }else{
                $hasta=array(0,0);
              }
              if ($h->getDesdeT()){
                $desdeT=explode(":",$h->getDesdeT());
              }else{
                $desdeT=array(0,0);
              }
              if ($h->getHastaT()){
                $hastaT=explode(":",$h->getHastaT());
              }else{
                $hastaT=array(0,0);
              }

              
              $horarios[$id]["fromH"]=$desde[0];
              $horarios[$id]["fromM"]=":".$desde[1];
              $horarios[$id]["toH"]=$hasta[0];
              $horarios[$id]["toM"]=":".$hasta[1];
              $horarios[$id]["fromHT"]=$desdeT[0];
              $horarios[$id]["fromMT"]=":".$desdeT[1];
              $horarios[$id]["toHT"]=$hastaT[0];
              $horarios[$id]["toMT"]=":".$hastaT[1];

              $horarios[$id]["closed"]=$h->getCerrado();
              $horarios[$id]["abierto"]=$h->getOpenAll();
              $horarios[$id]["partido"]=$h->getHorarioPartido();
              
        }
       
        return $this->render('BackendCustomerAdminBundle:Sucursal:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'dias' => $dias,
            'horarios' => $horarios
            
        ));
      }
      else
         throw new AccessDeniedException(); 
    }

    /**
    * Creates a form to edit a Sucursal entity.
    *                                       
    * @param Direccion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sucursal $entity)
    {
        $form = $this->createForm(new SucursalType(), $entity, array(
            'action' => $this->generateUrl('sucursal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    
      
    /**
     * Edits an existing Barrio entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODSUCURSAL')) {  
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);
        $dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();
        
        if (!$entity) {
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la sucursal.');
             return $this->redirect($this->generateUrl('sucursal'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $customerId = $this->getUser()->getId();
        $editForm = $this->createForm(new SucursalType(), $entity,array("customerId"=>$customerId));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            //update horarios
             $fromH=$request->get("fromH");
             $fromM=$request->get("fromM");
             $toH=$request->get("toH");
             $toM=$request->get("toM");
             $fromHT=$request->get("fromHT");
             $fromMT=$request->get("fromMT");
             $toHT=$request->get("toHT");
             $toMT=$request->get("toMT");
             $closed=$request->get("closed");
             $abierto=$request->get("abierto");
             $partido=$request->get("partido");

             foreach($entity->getHorarios() as $horario){
                 $dia=$horario->getDia()->getId();
                 if ( isset($closed[$dia]) && $closed[$dia] == 1){
                        $horario->setCerrado(true);
                        $horario->setOpenAll(false);
                        $horario->setHorarioPartido(false); 
                        $horario->setDesde("0:00");
                        $horario->setHasta("0:00");
                        $horario->setDesdeT("0:00");
                        $horario->setHastaT("0:00");
                 }elseif(isset($abierto[$dia]) &&  $abierto[$dia] == 1){
                        $horario->setOpenAll(true);
                        $horario->setHorarioPartido(false);
                        $horario->setCerrado(false);
                        $horario->setDesde("0:00");
                        $horario->setHasta("0:00");
                        $horario->setDesdeT("0:00");
                        $horario->setHastaT("0:00");
                }else{    
                       if(isset($partido[$dia]) &&  $partido[$dia] == 1){
                           $horario->setHorarioPartido(true);                 
                       }
                        $horario->setCerrado(false);
                        $horario->setOpenAll(false);
                        $horario->setDesde($fromH[$dia].$fromM[$dia]);
                        $horario->setHasta($toH[$dia].$toM[$dia]);
                        $horario->setDesdeT($fromHT[$dia] . $fromMT[$dia]);
                        $horario->setHastaT($toHT[$dia] . $toMT[$dia]);
                      
                 }
             
             }
             
            //update categorias
            $existCategorias=array();
            foreach($entity->getCategorias() as $c){
              $existCategorias[]=$c->getId();
            }
            
            if ($request->get("categorias")){ 
             $categorias=explode(",",$request->get("categorias"));
             foreach($categorias as $id){
                 if (!in_array($id,$existCategorias)){ //no esta la agrego la relacion
                    $cat = $em->getRepository('BackendAdminBundle:Categoria')->find($id);
                    $entity->addCategoria($cat);
                 }else{ //asi que lo quito de existCategorias
                      
                      $existCategorias=array_diff($existCategorias,array($id));
                 }   
              }
             
            }
            foreach($existCategorias as $d){  //elimino las que quedan porque ya no se seleccionaron
                  $cat = $em->getRepository('BackendAdminBundle:Categoria')->find($d);
                  $entity->removeCategoria($cat);
             } 
            
           //update subcategorias
            $existSubCategorias=array();
            foreach($entity->getSubcategorias() as $c){
              $existSubCategorias[]=$c->getId();
            }
           
            if ($request->get("subcategoria")){ 
             $subcategorias=$request->get("subcategoria");//explode(",",$request->get("subcategorias"));
           
             foreach($subcategorias as $k=>$id){
                 if (!in_array($id,$existSubCategorias)){ //no esta la agrego la relacion
                    $cat = $em->getRepository('BackendAdminBundle:Subcategoria')->find($id);
                    $entity->addSubcategoria($cat);
                 }else{ //asi que lo quito de existCategorias
                      $existSubCategorias=array_diff($existSubCategorias,array($id));
                 }   
              }
              
            }
            foreach($existSubCategorias as $d){  //elimino las que quedan porque ya no se seleccionaron
                 
                  $cat = $em->getRepository('BackendAdminBundle:Subcategoria')->find($d);
                  $entity->removeSubcategoria($cat);
             } 
        
        
            $em->persist($entity);
            $em->flush();
             $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos de la sucursal .');
            
        }
        
        $horarios=array();
        foreach($entity->getHorarios() as $h){
              $id=$h->getDia()->getId();
              if ($h->getDesde()){
                $desde=explode(":",$h->getDesde());
              }else{
                $desde=array(0,0);
              }
              
              if ($h->getHasta()){
                $hasta=explode(":",$h->getHasta()); 
              }else{
                $hasta=array(0,0);
              }
              if ($h->getDesdeT()){
                $desdet=explode(":",$h->getDesdeT());
              }else{
                $desdet=array(0,0);
              }
              if ($h->getHastaT()){
                $hastat=explode(":",$h->getHastaT());
              }else{
                $hastat=array(0,0);
              }
              $horarios[$id]["fromH"]=$desde[0];
              $horarios[$id]["fromM"]=":".$desde[1];
              $horarios[$id]["toH"]=$hasta[0];
              $horarios[$id]["toM"]=":".$hasta[1];
              $horarios[$id]["fromHT"]=$desdet[0];
              $horarios[$id]["fromMT"]=":".$desdet[1];
              $horarios[$id]["toHT"]=$hastat[0];
              $horarios[$id]["toMT"]=":".$hastat[1];
              $horarios[$id]["closed"]=$h->getCerrado();
              
              $horarios[$id]["abierto"]=$h->getOpenAll();
              $horarios[$id]["partido"]=$h->getHorarioPartido();
              
        }

        return $this->render('BackendCustomerAdminBundle:Sucursal:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'dias' => $dias,
            'horarios' => $horarios
            
            
        ));
      }
      else
         throw new AccessDeniedException();  
    }
	
    /**
     * Deletes a Direccion entity.
     *
     */
    
	public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELSUCURSAL')) { 
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la sucursal.');
             
            }
           else{
                  
            //TODO: no se puede borrar si tiene pedidos???
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos de la sucursal.');
            
            }
        }

        return $this->redirect($this->generateUrl('sucursal'));
      }
      else
       throw new AccessDeniedException(); 
    }

    /**
     * Creates a form to delete a Barrio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
  
  public function categoriasAction(Request $request){
       $em = $this->getDoctrine()->getManager();
       $categorias = $em->getRepository('BackendAdminBundle:Categoria')->findBy([], ['name' => 'ASC']);
       
       $data = array();
       $sucursalId = $request->get("sucursalId");
       $subSucursal = array();
       $catSucursal=array();
      
       if ($sucursalId != 0){
           $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($sucursalId);
       
                                        //recupero todas las subcategorias de la sucursal
         foreach($sucursal->getSubcategorias() as $ss){
               $subSucursal[]=$ss->getId();      
         }
         
         foreach($sucursal->getCategorias() as $sc){
               $catSucursal[]=$sc->getId();      
         }       
       }
        
       foreach($categorias as $c){
            $children=array();
            $subcategorias = $c->getSubcategorias();
             foreach($subcategorias as $s){
                $selected=false;
                if (in_array($s->getId(),$subSucursal)){
                  $selected = true;
                } 
                $children[]=array("a_attr"=>array("subcategoria_id"=> $s->getId()), "text"=> $s->getName(), "state"=>array("selected"=>$selected) );
             } 
            
          $data[]=array("a_attr"=>array("categoria_id"=> $c->getId()), "text"=> $c->getName(),"children"=>$children );
       }       
       
       $response = new Response(json_encode($data));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
       
    
  
  }
  
	/**
	*  Load Dia Entity to create Horario 
	*/
	
    public function loadHorarioAction(Request $request){
		 
       	$em = $this->getDoctrine()->getManager();

		$dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();
			
				if(!$dias){
				
					$data['existe']= false;
				
				}else{
						$data['existe'] = true;				
						
						foreach($dias as $dia){
							
								$d['id'] = $dia->getId();
								$d['name'] = $dia->getName();						
																				
								$resultados[] = $d;						
						}
						
						
						$data['resultados'] = $resultados;
								
				} 
					
			
			$response = new Response(json_encode($data));
			$response->headers->set('Content-Type', 'application/json');
			
			return $response;
    }
	
	/**
	 *  Listar horarios de atencion para la Sucursal 
	 */
	
	
    public function listarHorarioAction($id){
		 
        if ( $this->get('security.context')->isGranted('ROLE_VIEWSUCURSAL')) { 
         $em = $this->getDoctrine()->getManager();

         $entity = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);

         if (!$entity) {
             throw $this->createNotFoundException('No se ha encontrado al sucursal.');
         }

 		$horarios = $entity->getHorarios();
		$deleteForm = $this->createDeleteForm($id);
		
         return $this->render('BackendCustomerAdminBundle:Sucursal:listar_horarios.html.twig', array(
            'entity'      => $entity,
 			'horarios' 	  => $horarios,
 			'delete_form'   => $deleteForm->createView()            
         ));
       }
       else
          throw new AccessDeniedException();    
    }
	
	
	/**
	*  Load Dia Entity to create Horario 
	*/
	
	
    	
	/**
	* Creates a excel file to export data
	*
	*/
	
    
     public function exportarAction(Request $request)
    {
     if ( $this->get('security.context')->isGranted('ROLE_VIEWSUCURSAL')) {
         
         $em = $this->getDoctrine()->getManager();

       
        $search=$this->generateSQL($request->query->get("search-query")); 
           
       
        $query = $em->createQuery($search);
        
        $excelService = $this->get('xls.service_xls5');
                         
                            
        $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Zona')
                    ->setCellValue('B1', 'Calle')
					->setCellValue('C1','Numero')	
                    
                    ;
                    
        $resultados=$query->getResult();
        $i=2;
        foreach($resultados as $r)
        {
           $excelService->excelObj->setActiveSheetIndex(0)
                         ->setCellValue("A$i",$r->getZona()->getName())
                         ->setCellValue("B$i",$r->getCalle())
						 ->setCellValue("C$i",$r->getNumero())	 
                         ;
          $i++;
        }
                            
        $excelService->excelObj->getActiveSheet()->setTitle('Listado de Sucursales');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
        $fileName="sucursales_".date("Ymd").".xls";
        //create the response
        $response = $excelService->getResponse();
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        //$response->headers->set('Content-Disposition', 'filename='.$fileName);
        echo header("Content-Disposition: attachment; filename=$fileName");
        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->sendHeaders();
        return $response; 
        
        
        }
        else{
           throw new AccessDeniedException(); 
        }
    }
        
    
}

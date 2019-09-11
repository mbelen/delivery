<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Direccion;
use Backend\CustomerAdminBundle\Form\DireccionType;

/**
 * Direccion controller.
 *
 */
class DireccionController extends Controller
{

     public function generateSQL($search){
		 
		    $user=$this->getUser(); 
     
        $dql="SELECT u FROM BackendCustomerAdminBundle:Direccion u JOIN u.customers c where c.id = ".$user->getId() ;
        $search=mb_convert_case($search,MB_CASE_LOWER);
        
       
        if ($search) {
          
		     $dql.=" and u.calle like '%$search%' ";
         }
          
          $dql .=" order by u.calle"; 
        
        return $dql;
     
     }
 
	 public function getAddressByUser(){
		 
		 return $this->generateSQL("")
            ->getQuery()
            ->getResult();		 
	}	
     
     

    /**
     * Lists all Direcciones entities.
     *
     */
    public function indexAction(Request $request,$search)
    {
       if ( $this->get('security.context')->isGranted('ROLE_VIEWDIRECCION')) {
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
        return $this->render('BackendCustomerAdminBundle:Direccion:index.html.twig', 
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
     
    private function oneDefault(){
         
       	$customerId = $this->getUser()->getId();
        $dql="SELECT u FROM BackendCustomerAdminBundle:Direccion u JOIN u.customers c where c.id = ".$customerId ;
   		  $dql.=" and u.isDefault = 1 ";
          
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
        
        $resultados = $query->getResult();
        foreach($resultados as $r){
            $r->setIsDefault(false);
            $em->persist($r);
            $em->flush();
        }  
    
    } 
     
    public function createAction(Request $request, $type)

    {
        if ( $this->get('security.context')->isGranted('ROLE_ADDDIRECCION')) {
        $entity  = new Direccion();
        $form = $this->createForm(new DireccionType(), $entity);
        $form->bind($request);
        $addSucursal=false; 
    		$customerId = $this->getUser()->getId();
    		$em = $this->getDoctrine()->getManager();
    		$customer = $em->getRepository('BackendCustomerBundle:Customer')->find($customerId);
		
         
        if ($form->isValid()) {
            if ($entity->getIsDefault()){
              $this->oneDefault(); //quito el default a las demas direcciones
            }
			      $entity->addCustomer($customer);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se ha agregado una nueva direccion.');
            if ($type){
                return $this->redirect($this->generateUrl('direccion_edit', array('id' => $entity->getId())));
            }else{
                $addSucursal = true;
            }
            
            
        }
        
        return $this->render('BackendCustomerAdminBundle:Direccion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'type' => $type,
            'addSucursal'=>$addSucursal,
            'direccionId'=>$entity->getId()
           
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
    private function createCreateForm(Direccion $entity)
    {
        $form = $this->createForm(new DireccionType(), $entity, array(
            'action' => $this->generateUrl('direccion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new direccion entity.
     *
     */
    public function newAction(Request $request, $type)
    {
       if ( $this->get('security.context')->isGranted('ROLE_ADDDIRECCION')) {
        $entity = new Direccion();
        $form   = $this->createForm(new DireccionType(), $entity);
		    $addSucursal = false;
        return $this->render('BackendCustomerAdminBundle:Direccion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'type' => $type,
            'addSucursal'=>$addSucursal
            
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
        if ( $this->get('security.context')->isGranted('ROLE_MODDIRECCION')) { 
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Direccion')->find($id);

        if (!$entity) {
            
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la direccion.');
             return $this->redirect($this->generateUrl('direccion'));
        }

        $editForm = $this->createForm(new DireccionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendCustomerAdminBundle:Direccion:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
      }
      else
         throw new AccessDeniedException(); 
    }

    /**
    * Creates a form to edit a Barrio entity.
    *                                       
    * @param Direccion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Direccion $entity)
    {
        $form = $this->createForm(new DireccionType(), $entity, array(
            'action' => $this->generateUrl('direccion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Direccion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODDIRECCION')) {  
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Direccion')->find($id);

        if (!$entity) {
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la direccion.');
             return $this->redirect($this->generateUrl('direccion'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DireccionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
        
            if ($entity->getIsDefault()){
              $this->oneDefault(); //quito el default a las demas direcciones
            }
            $em->persist($entity);
            $em->flush();
             $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos de la direccion .');
            return $this->redirect($this->generateUrl('direccion_edit', array('id' => $id)));
        }

        return $this->render('BackendCustomerAdminBundle:Direccion:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
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
        if ( $this->get('security.context')->isGranted('ROLE_DELDIRECCION')) { 
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Direccion')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la direccion.');
             
            }
           else{
                  
            
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos de la direccion.');
            
            }
        }

        return $this->redirect($this->generateUrl('direccion'));
      }
      else
       throw new AccessDeniedException(); 
    }

    /**
     * Creates a form to delete a Direccion entity by id.
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
    
     public function exportarAction(Request $request)
    {
     if ( $this->get('security.context')->isGranted('ROLE_VIEWDIRECCION')) {
         
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
                            
        $excelService->excelObj->getActiveSheet()->setTitle('Listado de Direcciones');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
        $fileName="direcciones_".date("Ymd").".xls";
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
    
    public function getBarrioByZonaAction(Request $request)
    {
     
      $zona_id=$request->request->get("zona");
      $barrios = $this->getDoctrine()->getRepository('BackendAdminBundle:Barrio')->findBy(array("zona"=>$zona_id));
     
      $resultado=array();
      foreach($barrios as $v){
            $r=array();
            $r["id"]=$v->getId();
            $r["text"]=$v->getName();
            $resultado[] = $r;
       }
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    }
    
    public function getAllAction(Request $request){
       $dql=$this->generateSQL(false);
       $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($dql);
       $direcciones = $query->getResult();
       $resultado=array();
      foreach($direcciones as $d){
            $r=array();
            $r["id"]=$d->getId();
            $r["text"]=$d->__toString();
            $resultado[] = $r;
       }
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    }
        
    
}

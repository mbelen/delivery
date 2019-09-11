<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Variedad;
use Backend\CustomerAdminBundle\Form\VariedadType;

/**
 * Variedad controller.
 *
 */
class VariedadController extends Controller
{

     public function generateSQL($search){
         $user=$this->getUser();
        $dql="SELECT u FROM BackendCustomerAdminBundle:Variedad u where u.customer=".$user->getId()  ;
        $search=mb_convert_case($search,MB_CASE_LOWER);
        
       
        if ($search)
          $dql.=" and u.name like '%$search%' ";
          
        $dql .=" order by u.name"; 
        
        return $dql;
     
     }

    /**
     * Lists all Variedad entities.
     *
     */
    public function indexAction(Request $request,$search)
    {
       if ( $this->get('security.context')->isGranted('ROLE_VIEWVARIEDAD')) {
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
        return $this->render('BackendCustomerAdminBundle:Variedad:index.html.twig', 
        array('pagination' => $pagination,
        'delete_form' => $deleteForm->createView(),
        'search'=>$search
        ));
        
    }
     else
         throw new AccessDeniedException(); 
    }
    /**
     * Creates a new Producto entity.
     *
     */
    public function createAction(Request $request)
    {                          
        if ( $this->get('security.context')->isGranted('ROLE_ADDVARIEDAD')) {
        $entity  = new Variedad();
         $customerId=$this->getUser()->getId();
        $form = $this->createForm(new VariedadType(), $entity, array("customerId"=>$customerId));
		    $p = $request->request->get('backend_customeradminbundle_variedad');
        $productos = array();
        if (isset($p["productos"])){
        $productos = $p['productos'];
        }
        
		    $form->bind($request);
		    $em = $this->getDoctrine()->getManager();
         
        if ($form->isValid()) {
			      $entity->setCustomer($this->getUser());
             
			      
            foreach ($productos as $id) {
                
                $prod = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($id);
                $entity->addProducto($prod);                
            } 
			
			      $em->persist($entity);
            $em->flush();
			
            $this->get('session')->getFlashBag()->add('success' , 'Se ha agregado una nueva variedad.');
            return $this->redirect($this->generateUrl('variedad_edit', array('id' => $entity->getId())));
        	
		}        

        return $this->render('BackendCustomerAdminBundle:Variedad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
           
        ));
      }
      else
       throw new AccessDeniedException();
    }

    /**
    * Creates a form to create a Cliente entity.
    *
    * @param Producto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Variedad $entity)
    {
        $form = $this->createForm(new VariedadType(), $entity, array(
            'action' => $this->generateUrl('producto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Producto entity.
     *
     */
    public function newAction()
    {
       if ( $this->get('security.context')->isGranted('ROLE_ADDVARIEDAD')) {
        $entity = new Variedad();
         $customerId=$this->getUser()->getId();
        $form   = $this->createForm(new VariedadType(), $entity, array("customerId"=>$customerId));

        return $this->render('BackendCustomerAdminBundle:Variedad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
            
        ));
       }
       else
          throw new AccessDeniedException();
    }

  
    /**
     * Displays a form to edit an existing Producto entity.
     *
     */
    public function editAction($id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODVARIEDAD')) { 
        $em = $this->getDoctrine()->getManager();
          $customerId=$this->getUser()->getId();
        $entity = $em->getRepository('BackendCustomerAdminBundle:Variedad')->find($id);

        if (!$entity) {
            
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la variedad.');
             return $this->redirect($this->generateUrl('variedad'));
        }

        $editForm = $this->createForm(new VariedadType(), $entity, array("customerId"=>$customerId));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendCustomerAdminBundle:Variedad:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
      }
      else
         throw new AccessDeniedException(); 
    }

    /**
    * Creates a form to edit a Producto entity.
    *                                       
    * @param Producto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Variedad $entity)
    {
        $form = $this->createForm(new VariedadType(), $entity, array(
            'action' => $this->generateUrl('variedad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Producto entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODVARIEDAD')) {  
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Variedad')->find($id);

        if (!$entity) {
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la variedad.');
             return $this->redirect($this->generateUrl('variedad'));
        }
       
         $customerId=$this->getUser()->getId();
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new VariedadType(), $entity, array("customerId"=>$customerId));
		   
        $productos = $request->get("backend_customeradminbundle_variedad");
        
         $existProductos=array();
            foreach($entity->getProductos() as $p){
              $existProductos[]=$p->getId();
            }
            
        
        $editForm->bind($request);

        if ($editForm->isValid()) {
			
			   $em = $this->getDoctrine()->getManager();
			
			      
             
            if (isset($productos["productos"])){ 
                  
               foreach($productos["productos"] as $id){
                  
                   if (!in_array($id,$existProductos)){ //no esta la agrego la relacion
                                         
                      $prod = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($id);
                      $entity->addProducto($prod);
                   }else{ //asi que lo quito de $existProductos
                       
                        $existProductos=array_diff($existProductos,array($id));
                   }   
                }
             
            }
            
            foreach($existProductos as $d){  //elimino las que quedan porque ya no se seleccionaron
                 
                  $prod = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($d);
                  $entity->removeProducto($prod);
                  $prod->removeVariedades($entity);
                  
             } 
			    			
			      $em->persist($entity);
            $em->flush();
			
            $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos de la variedad.');
            return $this->redirect($this->generateUrl('variedad_edit', array('id' => $entity->getId())));
        }
           
        return $this->render('BackendCustomerAdminBundle:Variedad:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
      }
      else
         throw new AccessDeniedException();  
    }
    /**
     * Deletes a Variedad entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELVARIEDAD')) { 
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Variedad')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la variedad.');
             
            }
           else{
         
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos de la variedad.');
            
            }
        }

        return $this->redirect($this->generateUrl('variedad'));
      }
      else
       throw new AccessDeniedException(); 
    }

    /**
     * Creates a form to delete a Producto entity by id.
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
     if ( $this->get('security.context')->isGranted('ROLE_VIEWVARIEDAD')) {
         
         $em = $this->getDoctrine()->getManager();

       
        $search=$this->generateSQL($request->query->get("search-query")); 
           
       
        $query = $em->createQuery($search);
        
        $excelService = $this->get('xls.service_xls5');
                         
                            
        $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nombre')
                    ;
                    
        $resultados=$query->getResult();
        $i=2;
        foreach($resultados as $r)
        {
           $excelService->excelObj->setActiveSheetIndex(0)
                         ->setCellValue("A$i",$r->getName())
                         ;
          $i++;
        }
                            
        $excelService->excelObj->getActiveSheet()->setTitle('Listado de Productos');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->excelObj->setActiveSheetIndex(0);
        $excelService->excelObj->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        
        $fileName="categorias_".date("Ymd").".xls";
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
    public function getProductosAction(Request $request)
    {
      
      $categorias = $this->getDoctrine()->getRepository('BackendCustomerAdminBundle:Producto')->findAll();
     
      $resultado=array();
      foreach($categorias as $v){
            $r=array();
            $r["id"]=$v->getId();
            $r["text"]=$v->getName();
            $resultado[] = $r;
       }
       $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    }
    
}

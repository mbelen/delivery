<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Producto;
use Backend\CustomerAdminBundle\Form\ProductoType;

/**
 * Producto controller.
 *
 */
class ProductoController extends Controller
{

     public function generateSQL($search){
     
        $dql="SELECT u FROM BackendCustomerAdminBundle:Producto u "  ;
        $search=mb_convert_case($search,MB_CASE_LOWER);
        
       
        if ($search)
          $dql.=" where u.name like '%$search%' ";
          
        $dql .=" order by u.name"; 
        
        return $dql;
     
     }

    /**
     * Lists all Producto entities.
     *
     */
    public function indexAction(Request $request,$search)
    {
       if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {
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
        return $this->render('BackendCustomerAdminBundle:Producto:index.html.twig', 
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
        if ( $this->get('security.context')->isGranted('ROLE_ADDPRODUCTO')) {
        $entity  = new Producto();
        $customerId=$this->getUser()->getId();
        $form = $this->createForm(new ProductoType(), $entity, array("customerId"=>$customerId));
		
    		$s = $request->request->get('backend_customeradminbundle_producto');
    		$sucursales = $s['sucursales'];
        unset($s['sucursales']);
		
        $form->bind($request);
         
        if ($form->isValid()) {
			
            $em = $this->getDoctrine()->getManager();
			
		        
			
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se ha agregado un nuevo producto.');
            return $this->redirect($this->generateUrl('producto_edit', array('id' => $entity->getId())));
        }
        
        return $this->render('BackendCustomerAdminBundle:Producto:new.html.twig', array(
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
    private function createCreateForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType(), $entity, array(
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
       if ( $this->get('security.context')->isGranted('ROLE_ADDPRODUCTO')) {
        $entity = new Producto();
        $customerId=$this->getUser()->getId();
        $form   = $this->createForm(new ProductoType(), $entity, array("customerId"=>$customerId));

        return $this->render('BackendCustomerAdminBundle:Producto:new.html.twig', array(
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
        if ( $this->get('security.context')->isGranted('ROLE_MODPRODUCTO')) { 
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($id);

        if (!$entity) {
            
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el producto.');
             return $this->redirect($this->generateUrl('producto'));
        }
        $customerId=$this->getUser()->getId();
        $editForm = $this->createForm(new ProductoType(), $entity, array("customerId"=>$customerId, "productoId"=>$entity->getId()));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendCustomerAdminBundle:Producto:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'showVariedades' => true
            
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
    private function createEditForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType(), $entity, array(
            'action' => $this->generateUrl('producto_update', array('id' => $entity->getId())),
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
        if ( $this->get('security.context')->isGranted('ROLE_MODPRODUCTO')) {  
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($id);

        if (!$entity) {
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el producto.');
             return $this->redirect($this->generateUrl('producto'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $customerId=$this->getUser()->getId();
        $editForm = $this->createForm(new ProductoType(), $entity, array("customerId"=>$customerId,"productoId"=>$entity->getId()));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
             $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos del producto .');
            return $this->redirect($this->generateUrl('producto_edit', array('id' => $id)));
        }

        return $this->render('BackendCustomerAdminBundle:Producto:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'showVariedades' => true
            
        ));
      }
      else
         throw new AccessDeniedException();  
    }
    /**
     * Deletes a Producto entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELPRODUCTO')) { 
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Producto')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el producto.');
             
            }
           else{
            
              //TODO: si se borran y hay pedidos? hay compras?
            
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos del producto.');
            
            }
        }

        return $this->redirect($this->generateUrl('producto'));
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
     if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {
         
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
    
    public function getSubcategoriaByCategoriaAction(Request $request)
    {
     
      $categoria_id=$request->request->get("categoria");
      $subcategorias = $this->getDoctrine()->getRepository('BackendAdminBundle:Subcategoria')->findBy(array("categoria"=>$categoria_id));
     
      $resultado=array();
      foreach($subcategorias as $v){
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

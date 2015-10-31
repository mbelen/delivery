<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Pedido;
use Backend\CustomerAdminBundle\Form\PedidoType;


/**
 * Pedido controller.
 *
 */
class PedidoController extends Controller
{

     public function generateSQL($search){
     
		$user=$this->getUser();	
     
		$dql="SELECT u FROM BackendCustomerAdminBundle:Pedido u JOIN u.sucursal s where s.customer = ".$user->getId();
               
        $search=mb_convert_case($search,MB_CASE_LOWER);
               
        if ($search)
          
          $dql.=" where u.id like '%$search%' ";
          
		  $dql .=" order by u.id"; 
        
        return $dql;
     
     }

    /**
     * Lists all Pedido entities.
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

        $status = $em->getRepository('BackendCustomerAdminBundle:Status')->findAll();

        $deleteForm = $this->createDeleteForm(0);
        return $this->render('BackendCustomerAdminBundle:Pedido:index.html.twig', 
        array('pagination' => $pagination,
        'delete_form' => $deleteForm->createView(),
        'search'=>$search,
        'status' => $status
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
        $entity  = new Pedido();
        $form = $this->createForm(new PedidoType(), $entity);
		
		$s = $request->request->get('backend_customeradminbundle_pedido');
		$sucursales = $s['sucursales'];
        unset($s['sucursales']);
		
        $form->bind($request);
         
        if ($form->isValid()) {
			
            $em = $this->getDoctrine()->getManager();
			
		    foreach ($sucursales as $id) {
		                   
		         $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($id);		             	   
                 $sucursal->addProducto($entity);
   			     $em->persist($sucursal);
	     		 $entity->addSucursal($sucursal);
		                   
	         }
			
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
    private function createCreateForm(Pedido $entity)
    {
        $form = $this->createForm(new PedidoType(), $entity, array(
            'action' => $this->generateUrl('pedido_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pedido entity.
     *
     */
    public function newAction()
    {
       if ( $this->get('security.context')->isGranted('ROLE_ADDPRODUCTO')) {
        $entity = new Pedido();
        $form   = $this->createForm(new PedidoType(), $entity);

        return $this->render('BackendCustomerAdminBundle:Pedido:new.html.twig', array(
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

        $entity = $em->getRepository('BackendCustomerAdminBundle:Pedido')->find($id);

        if (!$entity) {
            
             $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el pedido.');
             return $this->redirect($this->generateUrl('pedido'));
        }

        $editForm = $this->createForm(new PedidoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendCustomerAdminBundle:Pedido:edit.html.twig', array(
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
    private function createEditForm(Pedido $entity)
    {
        $form = $this->createForm(new PedidoType(), $entity, array(
            'action' => $this->generateUrl('pedido_update', array('id' => $entity->getId())),
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
        $editForm = $this->createForm(new PedidoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
             $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos del producto .');
            return $this->redirect($this->generateUrl('pedido_edit', array('id' => $id)));
        }

        return $this->render('BackendCustomerAdminBundle:Pedido:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
      }
      else
         throw new AccessDeniedException();  
    }
    /**
     * Deletes a Pedido entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELPRODUCTO')) { 
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Pedido')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el pedido.');
             
            }
            else{

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos del pedido.');
            
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
    
	public function updateStatusAction($id,Request $request)
    {
        if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {

            $statusId = $request->get("status");
            $comentarios = $request->get("comentarios");
            $em = $this->getDoctrine()->getManager();
            $pedido = $em->getRepository('BackendCustomerAdminBundle:Pedido')->find($id);
            $status = $em->getRepository('BackendCustomerAdminBundle:Status')->find($statusId);
            $pedido->setStatus($status);
            $cliente = $pedido->getCliente();
            $sucursal = $pedido->getSucursal();

            $this->sendStatusPedidosEmails($cliente,$sucursal,$status,$comentarios);

            return $this->redirect($this->generateUrl('producto'));
        }
    }


    public function printAction(Request $request, $id)
   {
      if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {
          $em = $this->getDoctrine()->getManager();
          $entity = $em->getRepository('BackendCustomerAdminBundle:Pedido')->find($id);
    
          if (!$entity) {
              $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el pedido.');
              return $this->redirect($this->generateUrl('pedido' ));
          }
          else{
            require_once($this->get('kernel')->getRootDir().'/config/dompdf_config.inc.php');
            $dompdf = new \DOMPDF();
            
            $html= $this->renderView('BackendCustomerAdminBundle:Pedido:constancia.html.twig',
              array('entity'=>$entity)
            );
            $dompdf->load_html($html);
            $dompdf->render();
            $fileName="pedido_".$id.".pdf";
            $response= new Response($dompdf->output(), 200, array(
            	'Content-Type' => 'application/pdf; charset=utf-8'
            ));
            $response->headers->set('Content-Disposition', 'attachment;filename='.$fileName);
            return $response;
          }
      }
        else{
           throw new AccessDeniedException(); 
        }
   
   }

    private function sendStatusPedidosEmails($customer, $sucursal,$status,$comentarios){

        $em = $this->getDoctrine()->getManager();
        $empresa = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("empresa");
        $email_site = $em->getRepository('BackendUserBundle:Seteo')->findOneByName("email");

        $messageCustomer = \Swift_Message::newInstance()
            ->setSubject("Pedido enviado a"+ $sucursal->getName()+" mediante el sitio "+$empresa->getValue())
            ->setFrom($email_site->getValue())
            ->setTo($customer->getEmail())
            ->setBody(
                $this->renderView(
                    'BackendCustomerAdminBundle:Pedido:status_customer_email.html.twig',
                    array('customerName' => $customer->getName(),'sucursalName' => $sucursal->getName(),'status'=> $status->getName(),
                        'comentarios' => $comentarios, 'empresa' =>$empresa->getValue(),'sucursalTelefono' =>$sucursal->getPhone(),
                        'sucursalEmail'=>$sucursal->getEmail() )
                ),'text/html'
            );



        @$this->get('mailer')->send($messageCustomer);

    }


     public function exportarAction(Request $request)
    {
     if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {
         
         $em = $this->getDoctrine()->getManager();

       
        $search=$this->generateSQL($request->query->get("search-query"));

       
        $query = $em->createQuery($search);

         $excelService = $this->get('xls.service_xls5');
                         
                            
        $excelService->excelObj->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Pedido nº')
                    ->setCellValue('B1','Fecha')
                    ->setCellValue('C1','Sucursal')
                    ->setCellValue('D1','Cliente')
                    ->setCellValue('E1','Direccion')
                    ->setCellValue('F1','')
                    ;
                    
        $resultados=$query->getResult();
        $i=2;
        foreach($resultados as $r)
        {
           $excelService->excelObj->setActiveSheetIndex(0)
                         ->setCellValue("A$i",$r->getId())
                         ->setCellValue("B$i",$r->getCreatedAt())
                         ->setCellValue("C$i",$r->getSucursal()->getName())
                         ->setCellValue("D$i",$r->getCliente()->getName())
                         ->setCellValue("E$i",$r->getCliente()->getDireccion()->getCalle())
                         ->setCellValue("F$i",$r->getMedioPago()->getName())
                         ->setCellValue("G$i",$r->getDetalle())
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

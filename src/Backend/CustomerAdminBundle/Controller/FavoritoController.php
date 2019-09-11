<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Favorito;


/**
 * Favorito controller.
 *
 */
class FavoritoController extends Controller
{

     public function generateSQL($search){
		 
		    $user_id=$this->getUser()->getId(); 
     
        $dql="SELECT u FROM BackendCustomerAdminBundle:Favorito u JOIN u.sucursal s where u.customer = ".$user_id ;
        $search=mb_convert_case($search,MB_CASE_LOWER);
        
       
        if ($search) {
          
		     $dql.=" and s.name like '%$search%' ";
         }
          
          $dql .=" order by s.name"; 
        
        return $dql;
     
     }

    /**
     * Lists all Favoritoes entities.
     *
     */
    public function indexAction(Request $request,$search)
    {
       if ( $this->get('security.context')->isGranted('ROLE_VIEWFAVORITO')) {
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
        return $this->render('BackendCustomerAdminBundle:Favorito:index.html.twig', 
        array('pagination' => $pagination,
        'delete_form' => $deleteForm->createView(),
        'search'=>$search
        ));
        
    }
     else
         throw new AccessDeniedException(); 
    }
    
	
    /**
     * Deletes a Favorito entity.
     *
     */
    
	public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELFAVORITO')) { 
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Favorito')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el favorito.');
             
            }
           else{
                  
            
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos del favorito.');
            
            }
        }

        return $this->redirect($this->generateUrl('favorito'));
      }
      else
       throw new AccessDeniedException(); 
    }

    /**
     * Creates a form to delete a Favorito entity by id.
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
    
    
        
    
}

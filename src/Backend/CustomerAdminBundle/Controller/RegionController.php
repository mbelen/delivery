<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Backend\CustomerAdminBundle\Entity\Region;
use Backend\CustomerAdminBundle\Entity\Coordenada;


/**
 * Region controller.
 *
 */
class RegionController extends Controller
{

      

      public function generateSQL($search, $sucursalId)
    {

       

        $dql = "SELECT u FROM BackendCustomerAdminBundle:Region u where  u.sucursal =". $sucursalId;
        $query= mb_convert_case($search, MB_CASE_LOWER);

        if ($query)
            $dql .= " and u.name like '%".$query."%' ";

        $dql .= " order by u.name";

        return $dql;

    }

    /**
     * Lists all Banners entities.
     *
     */
    public function indexAction($sucursal, $search)
    {
        $em = $this->getDoctrine()->getManager();
        $sucursalObject = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($sucursal);

        if ($this->get('security.context')->isGranted('ROLE_VIEWSUCURSAL') && $sucursalObject) {
            

            $dql = $this->generateSQL($search,$sucursal);
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1)/*page number*/,
                $this->container->getParameter('max_on_listepage')/*limit per page*/
            );

            $deleteForm = $this->createDeleteForm(0);
            return $this->render('BackendCustomerAdminBundle:Region:index.html.twig',
                array('pagination' => $pagination,
                    'delete_form' => $deleteForm->createView(),
                    'search' => $search,
                    'sucursal'=>$sucursal,
                    'sucursalName'=>$sucursalObject->getName()
                ));

        } else
            throw new AccessDeniedException();
    }

    /**
     * Creates a new Producto entity.
     *
     */
    
    /**
     * Displays a form to create a new Banner entity.
     *
     */
    public function newAction($id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_ADDSUCURSAL')) {
  
            return $this->render('BackendCustomerAdminBundle:Region:new.html.twig', array(
                "sucursalid"=>$id
            ));
        }
        else
            throw new AccessDeniedException();
    }

    public function addAction(Request $request){
        
        $resultado=array("status"=>0,"message"=>'');

        $sucursalId = $request->get("sucursal");  
        $name = $request->get("name");  
        $vertices = explode("),",$request->get("coordenadas"));
        //try{
        
            $em = $this->getDoctrine()->getManager();
            $region = new Region();
            $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($sucursalId);   
            $region->setName($name);
            $region->setSucursal($sucursal);
            $em->persist($region);
            $em->flush();

            foreach($vertices as $v){
                $value=str_replace(array("(",")"),"",$v);
                list($lat,$lon)=explode(",",$value);
                
                $coordenada = new Coordenada();
                $coordenada->setLat($lat);
                $coordenada->setLng($lon);
                $coordenada->setRegion($region);
                $em->persist($coordenada);
                $em->flush();
               
                


            }
            
        /*}catch(Exception $e){
            $resultado["status"]=1;
            $resultado["message"]="No se pudo guardar la región"
        }*/

        $response = new Response(json_encode($resultado));
        
        $response->headers->set('Content-Type', 'application/json');
  
       return $response;

    }

    /**
     * Displays a form to edit an existing Producto entity.
     *
     */
    public function editAction($id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODREGION')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('BackendCustomerAdminBundle:Region')->find($id);

            if (!$entity) {

                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la región.');
                return $this->redirect($this->generateUrl('sucursal'));
            }
           
           $vertices='';
           $poligono='';
           $separador='';

           foreach($entity->getCoordenadas() as $c){
                $vertices .=$separador."(".$c->getLat().", ".$c->getLng().")";
                $poligono .=$separador." new google.maps.LatLng(".$c->getLat().", ".$c->getLng().") "; 
                $separador=",";
           }
           
            return $this->render('BackendCustomerAdminBundle:Region:edit.html.twig', array(
                
                    "sucursalid"=>$entity->getSucursal()->getId(),
                    "region"=>$entity,
                    "vertices"=>$vertices,
                    "poligono"=>$poligono

            ));
        }
        else
            throw new AccessDeniedException();
    }

   public function updateAction(Request $request){
        
        $resultado=array("status"=>0,"message"=>'');

        $sucursalId = $request->get("sucursal");  
        $name = $request->get("name");  
        $vertices = explode("),",$request->get("coordenadas"));
        $regionId = $request->get("region");
        //try{
        
            $em = $this->getDoctrine()->getManager();
           
            $region = $em->getRepository('BackendCustomerAdminBundle:Region')->find($regionId);   
            
            $list=$region->getCoordenadas();
            //elimino las coordenadas anteriores
            foreach($list as $c){
                $em->remove($c);
                $em->flush();
            }
           
            //agrego las nuevas coordenadas
            foreach($vertices as $v){
                $value=str_replace(array("(",")"),"",$v);
           
                list($lat,$lon)=explode(",",$value);
                
                $coordenada = new Coordenada();
                $coordenada->setLat($lat);
                $coordenada->setLng($lon);
                $coordenada->setRegion($region);
                $em->persist($coordenada);
                $em->flush();
    

            }
            
        /*}catch(Exception $e){
            $resultado["status"]=1;
            $resultado["message"]="No se pudo guardar la región"
        }*/

        $response = new Response(json_encode($resultado));
        
        $response->headers->set('Content-Type', 'application/json');
  
       return $response;

    }
    
    /**
     * Deletes a Banner entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELREGION')) {
            $form = $this->createDeleteForm($id);
            $form->bind($request);
            $sucursal=0;

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('BackendCustomerAdminBundle:Region')->find($id);

                if (!$entity) {
                    $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la region.');

                }
                else{
                    $sucursal = $entity->getSucursal()->getId(); 
                    //borro la región y sus coordenadas
                    $coordenadas = $entity->getCoordenadas();
                    foreach($coordenadas as $c){
                        $em->remove($c);
                        $em->flush();    
                    }

                    $em->remove($entity);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos de la region.');

                }
            }
            if ($sucursal != 0){
                return $this->redirect($this->generateUrl('region',array("sucursal"=>$sucursal)));
            }else{
                return $this->redirect($this->generateUrl('sucursal'));
            }        
        }
        else
            throw new AccessDeniedException();
    }

    /**
     * Creates a form to delete a Banner entity by id.
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
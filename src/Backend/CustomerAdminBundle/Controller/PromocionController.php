<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Backend\CustomerAdminBundle\Entity\Promocion;
use Backend\AdminBundle\Entity\HorarioPromo;
use Backend\CustomerAdminBundle\Form\PromocionType;


/**
 * Promocion controller.
 *
 */
class PromocionController extends Controller
{

    public function generateSQL($search)
    {

        $user = $this->getUser();

        $dql = "SELECT u FROM BackendCustomerAdminBundle:Promocion u JOIN u.sucursales s where s.customer = " . $user->getId();
        $search = mb_convert_case($search, MB_CASE_LOWER);

        if ($search)
            $dql .= " and u.name like '%$search%' ";

        $dql .= " order by u.name";

        return $dql;

    }

    /**
     * Lists all Promocion entities.
     *
     */
    public function indexAction(Request $request, $search)
    {
        if ($this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {
            $em = $this->getDoctrine()->getManager();

            $dql = $this->generateSQL($search);
            $query = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1)/*page number*/,
                $this->container->getParameter('max_on_listepage')/*limit per page*/
            );

            $deleteForm = $this->createDeleteForm(0);
            return $this->render('BackendCustomerAdminBundle:Promocion:index.html.twig',
                array('pagination' => $pagination,
                    'delete_form' => $deleteForm->createView(),
                    'search' => $search
                ));

        } else
            throw new AccessDeniedException();
    }

    /**
     * Creates a new Producto entity.
     *
     */
    public function createAction(Request $request)
    {
        if ( $this->get('security.context')->isGranted('ROLE_ADDPRODUCTO')) {
            $entity  = new Promocion();
            $customerId=$this->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $form = $this->createForm(new PromocionType(), $entity, array("customerId"=>$customerId));

            $form->bind($request);
            $dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();

            if ($form->isValid()) {

                // add horarios promos y repeticiones

                $fromH=$request->get("fromH");
                $fromM=$request->get("fromM");
                $toH=$request->get("toH");
                $toM=$request->get("toM");

                $abierto=$request->get("abierto");

                foreach($dias as $d) {
                    $horario = new HorarioPromo();
                    $horario->setDia($d);
                    //esta cerrado
                    if (isset($abierto[$d->getId()]) && $abierto[$d->getId()] == 1) {
                        $horario->setAllDay(true);
                    } else {

                        $horario->setAllDay(false);
                        $horario->setDesde($fromH[$d->getId()] . $fromM[$d->getId()]);
                        $horario->setHasta($toH[$d->getId()] . $toM[$d->getId()]);
                    }
                    $em->persist($horario);
                    $em->flush();
                    $entity->addHorario($horario);
                }

                $promoType = $entity->getType();

                $productos = $entity->getProductos();

                if($promoType->getId() == 1){

                    $porcentaje = $entity->getDetail();

                    foreach($productos as $prod){

                        $price = $prod->getPrice();
                        $prod->setPromoPrice($price*0.25);

                        $em->persist($prod);
                        $em->flush();
                    }
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success' , 'Se ha agregado una nueva promocion.');
                return $this->redirect($this->generateUrl('promocion_edit', array('id' => $entity->getId())));
            }

            return $this->render('BackendCustomerAdminBundle:Promocion:new.html.twig', array(
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
     * @param Promocion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Promocion $entity)
    {
        $form = $this->createForm(new PromocionType(), $entity, array(
            'action' => $this->generateUrl('promocion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Promocion entity.
     *
     */
    public function newAction()
    {
        if ( $this->get('security.context')->isGranted('ROLE_ADDPRODUCTO')) {
            $entity = new Promocion();
            $customerId=$this->getUser()->getId();
            $em = $this->getDoctrine()->getManager();
            $dias = $em->getRepository('BackendAdminBundle:Dia')->findAll();
            $form   = $this->createForm(new PromocionType(), $entity, array("customerId"=>$customerId));

            return $this->render('BackendCustomerAdminBundle:Promocion:promocion.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
                'dias'   => $dias

            ));
        }
        else
            throw new AccessDeniedException();
    }

    /**
     * Displays a form to edit an existing Promocion entity.
     *
     */
    public function editAction($id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_MODPRODUCTO')) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BackendCustomerAdminBundle:Promocion')->find($id);

            if (!$entity) {

                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el producto.');
                return $this->redirect($this->generateUrl('promocion'));
            }
            $customerId=$this->getUser()->getId();
            $editForm = $this->createForm(new PromocionType(), $entity, array("customerId"=>$customerId));
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

                $horarios[$id]["fromH"]=$desde[0];
                $horarios[$id]["fromM"]=":".$desde[1];
                $horarios[$id]["toH"]=$hasta[0];
                $horarios[$id]["toM"]=":".$hasta[1];
                $horarios[$id]["abierto"]=$h->getAllDay();

            }

            return $this->render('BackendCustomerAdminBundle:Promocion:edit.html.twig', array(
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
     * Creates a form to edit a Producto entity.
     *
     * @param Producto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Promocion $entity)
    {
        $form = $this->createForm(new PromocionType(), $entity, array(
            'action' => $this->generateUrl('promocion_update', array('id' => $entity->getId())),
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

            $entity = $em->getRepository('BackendCustomerAdminBundle:Promocion')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado la promocion.');
                return $this->redirect($this->generateUrl('producto'));
            }

            $deleteForm = $this->createDeleteForm($id);
            $customerId=$this->getUser()->getId();
            $editForm = $this->createForm(new PromocionType(), $entity, array("customerId"=>$customerId));
            $editForm->bind($request);

            if ($editForm->isValid()) {

                //update horarios
                $fromH=$request->get("fromH");
                $fromM=$request->get("fromM");
                $toH=$request->get("toH");
                $toM=$request->get("toM");
                $abierto=$request->get("abierto");

                foreach($entity->getHorarios() as $horario){
                    $dia=$horario->getDia()->getId();
                    if(isset($abierto[$dia]) &&  $abierto[$dia] == 1){
                        $horario->setAllDay(true);
                        $horario->setDesde("0:00");
                        $horario->setHasta("0:00");

                    }else{

                        $horario->setAllDay(false);
                        $horario->setDesde($fromH[$dia].$fromM[$dia]);
                        $horario->setHasta($toH[$dia].$toM[$dia]);
                    }

                }

                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos de la promocion .');
                return $this->redirect($this->generateUrl('promocion_edit', array('id' => $id)));
            }

            return $this->render('BackendCustomerAdminBundle:Promocion:edit.html.twig', array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),


            ));
        }
        else
            throw new AccessDeniedException();
    }

    public function toUpdateStatusAction(Request $request)
    {
        if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {

            $id = $request->request->get('id');
            $em = $this->getDoctrine()->getManager();

            $statusId = $request->get("status");
            $em = $this->getDoctrine()->getManager();
            $promocion = $em->getRepository('BackendCustomerAdminBundle:Promocion')->find($id);

            $promocion->setStatus($statusId);
            $em->persist($promocion);
            $em->flush();

            $data['ok'] = true;
            $data['status'] = $statusId;
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

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
                $entity = $em->getRepository('BackendCustomerAdminBundle:Promocion')->find($id);

                if (!$entity) {
                    $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el promocion.');

                }
                else{

                    //TODO: si se borran y hay pedidos? hay compras?

                    $em->remove($entity);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos de la promocion.');

                }
            }

            return $this->redirect($this->generateUrl('promocion'));
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



}
<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Backend\CustomerAdminBundle\Entity\Banner;
use Backend\CustomerAdminBundle\Form\BannerType;


/**
 * Banner controller.
 *
 */
class BannerController extends Controller
{

    public function generateSQL($search)
    {

        $user = $this->getUser();

        $dql = "SELECT u FROM BackendCustomerAdminBundle:Banner u JOIN u.sucursal s where s.customer = " . $user->getId();
        $search = mb_convert_case($search, MB_CASE_LOWER);

        if ($search)
            $dql .= " and u.name like '%".$search."%' ";

        $dql .= " order by u.name";

        return $dql;

    }

    /**
     * Lists all Banners entities.
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
            return $this->render('BackendCustomerAdminBundle:Banner:index.html.twig',
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
            $entity  = new Banner();
            $customerId=$this->getUser()->getId();
            $form = $this->createForm(new BannerType(), $entity, array("customerId"=>$customerId));

            $form->bind($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $sucursal = $entity->getSucursal();
                $bannerActual = $entity->getId();

                $banners = $sucursal->getBanners();

                foreach($banners as $ba){  // despublico todos los otros banners vigentes

                    if($ba->getIsActive() == true && $ba->getId() != $bannerActual){

                        $ba->setIsActive(false);
                        $em->persist($ba);
                        $em->flush();
                        $sucursal->addBanner($ba);
                    }
                }
                $em->persist($sucursal);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success' , 'Se ha agregado un nuevo banner.');
                return $this->redirect($this->generateUrl('banner_edit', array('id' => $entity->getId())));
            }

            return $this->render('BackendCustomerAdminBundle:Banner:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView()

            ));
        }
        else
            throw new AccessDeniedException();
    }

    /**
     * Creates a form to create a Banner entity.
     *
     * @param Banner $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Banner $entity)
    {
        $form = $this->createForm(new BannerType(), $entity, array(
            'action' => $this->generateUrl('banner_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Banner entity.
     *
     */
    public function newAction()
    {
        if ( $this->get('security.context')->isGranted('ROLE_ADDPRODUCTO')) {
            $entity = new Banner();
            $customerId=$this->getUser()->getId();
            $form   = $this->createForm(new BannerType(), $entity, array("customerId"=>$customerId));

            return $this->render('BackendCustomerAdminBundle:Banner:new.html.twig', array(
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

            $entity = $em->getRepository('BackendCustomerAdminBundle:Banner')->find($id);

            if (!$entity) {

                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el banner.');
                return $this->redirect($this->generateUrl('banner'));
            }
            $customerId=$this->getUser()->getId();
            $editForm = $this->createForm(new BannerType(), $entity, array("customerId"=>$customerId));
            $deleteForm = $this->createDeleteForm($id);

            return $this->render('BackendCustomerAdminBundle:Banner:edit.html.twig', array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),


            ));
        }
        else
            throw new AccessDeniedException();
    }

    /**
     * Creates a form to edit a Banner entity.
     *
     * @param Banner $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Banner $entity)
    {
        $form = $this->createForm(new BannerType(), $entity, array(
            'action' => $this->generateUrl('banner_update', array('id' => $entity->getId())),
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

            $entity = $em->getRepository('BackendCustomerAdminBundle:Banner')->find($id);

            if (!$entity) {
                $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el banner.');
                return $this->redirect($this->generateUrl('banner'));
            }

            $deleteForm = $this->createDeleteForm($id);
            $customerId=$this->getUser()->getId();
            $editForm = $this->createForm(new BannerType(), $entity, array("customerId"=>$customerId));
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success' , 'Se han actualizado los datos del banner.');
                return $this->redirect($this->generateUrl('banner_edit', array('id' => $id)));
            }

            return $this->render('BackendCustomerAdminBundle:Banner:edit.html.twig', array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),


            ));
        }
        else
            throw new AccessDeniedException();
    }
    /**
     * Deletes a Banner entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ( $this->get('security.context')->isGranted('ROLE_DELPRODUCTO')) {
            $form = $this->createDeleteForm($id);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('BackendCustomerAdminBundle:Banner')->find($id);

                if (!$entity) {
                    $this->get('session')->getFlashBag()->add('error' , 'No se ha encontrado el banner.');

                }
                else{

                    $em->remove($entity);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success' , 'Se han borrado los datos del banner.');

                }
            }

            return $this->redirect($this->generateUrl('banner'));
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
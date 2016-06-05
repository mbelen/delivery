<?php

namespace Backend\CustomerAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\RuntimeException;
use Backend\CustomerAdminBundle\Entity\Producto;
use Backend\CustomerAdminBundle\Entity\Variedad;
use Backend\CustomerAdminBundle\Form\ProductoType;
use ZipArchive;

/**
 * Producto controller.
 *
 */
class ProductoController extends Controller
{

     public function generateSQL($search){
     
        $user=$this->getUser();
            
        $dql="SELECT u FROM BackendCustomerAdminBundle:Producto u JOIN u.sucursales s where s.customer = ".$user->getId();
        $search=mb_convert_case($search,MB_CASE_LOWER);
       
        if ($search)
          $dql.=" and u.name like '%$search%' or u.description like '%$search%' or u.code like '%$search%'";
          
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
        $editForm = $this->createForm(new ProductoType(), $entity, array("customerId"=>$customerId));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendCustomerAdminBundle:Producto:edit.html.twig', array(
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
        $editForm = $this->createForm(new ProductoType(), $entity, array("customerId"=>$customerId));
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
            
            $entity->setIsActive(false);   
            $em->flush();
            $this->get('session')->getFlashBag()->add('success' , 'Se ha despublicado el producto.');
            
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

    /*
     * Genera el formulario para la carga masiva de productos
     *
     */

    public function importarAction(Request $request){

        if ( $this->get('security.context')->isGranted('ROLE_IMPORTAR')) {

            $user=$this->getUser();
            $em = $this->getDoctrine()->getManager();

            $dql="SELECT u FROM BackendCustomerAdminBundle:Sucursal u JOIN u.customer c where c.id = ".$user->getId();

            $query = $em->createQuery($dql);

            $sucursales=$query->getResult();
            if ($request->getMethod() == 'POST') {
              $sucursal='';
              $excel='';
              $images='';
              $continuar=true;
             
              if ($request->get("sucursal")){   
                $sucursal=$request->get("sucursal");
              }else{
                 $this->get('session')->getFlashBag()->add('error' , 'No se indico una sucursal.');
                 $continuar=false;
              }
             
             $excel = $request->files->get('excell');
             if (null === $excel){
                $continuar=false;
                $this->get('session')->getFlashBag()->add('error' , 'No se indico un archivo de productos.');
             }else{
                 $path= pathinfo($excel->getClientOriginalName());
                 $ext = $path["extension"];
                 $size = $excel->getClientSize();
                 echo "producto extension $ext size $size maximo".$excel->getMaxFilesize();
                 if ($ext == "xls" || $ext == "xlsx"){
                    if ($size < $excel->getMaxFilesize()){
                        $filename = sha1(uniqid(mt_rand(), true));
                        $dir = __DIR__.'/../../../../web/uploads/importar/'.$user->getId();
                        $excel->move($dir, $filename);
                    }else{
                       $continuar=false;
                       $this->get('session')->getFlashBag()->add('error' , 'El archivo de productos es demasiado grande.'); 
                    }
                }else{
                    $continuar=false;
                    $this->get('session')->getFlashBag()->add('error' , 'El archivo de productos no tiene extensión xls o xlsx.');
                }
             }

             $images = $request->files->get('images');
             if (null !== $images){
                 $path = pathinfo($images->getClientOriginalName());
                 $ext = $path["extension"];
                 $size = $images->getClientSize();
                 echo "imagen extension $ext size $size maximo".$images->getMaxFilesize();
                 if ($ext == "zip" ){
                    if ($size < $images->getMaxFilesize()){
                         $filenameImg = sha1(uniqid(mt_rand(), true)).".zip";
                         $dir = __DIR__.'/../../../../web/uploads/importar/'.$user->getId();
                         $images->move($dir, $filenameImg);
                         $targetzip = $dir."/".$filenameImg;
                         $targetdir = __DIR__.'/../../../../web/uploads/productos/'.$user->getId(); //poner los productos en carpetas por usuario
                         $zip = new ZipArchive();
                         
                         $x = $zip->open($targetzip);  // open the zip file to extract
                         if ($x === true) {
                                $zip->extractTo($targetdir); // place in the directory with same name  
                                $zip->close();
                     
                                unlink($targetzip);
                        }else{
                            $continuar=false;
                            $this->get('session')->getFlashBag()->add('error' , 'El archivo de images no pudo descomprimirse. Verifique que este bien formado.'); 
                        }
                    }else{
                        $continuar=false;
                        $this->get('session')->getFlashBag()->add('error' , 'El archivo de images es demasiado grande.');  
                    }
               } else{
                    $continuar=false;
                    $this->get('session')->getFlashBag()->add('error' , 'El archivo de imagenes no tiene extensión zip .');
               }
             }

             if ($continuar){

                    return $this->render('BackendCustomerAdminBundle:Producto:importar.html.twig',array("filename"=>$dir."/".$filename,"sucursales"=>implode(",",$sucursal)));
             }
            }
            
            return $this->render('BackendCustomerAdminBundle:Producto:masiva.html.twig', array(
                'sucursales' => $sucursales
                    ));

            } else{
                throw new AccessDeniedException();
            }
    }

    /**
     * @param Request $request
     *
     * Proceso los datos del excel
     */

    public function procesarExcelAction(Request $request){
            $user=$this->getUser();
            $resultado=array("status"=>0,"message"=>'', "total"=>0, "row"=>2, "error"=>'');
            $filename =$request->get("filename");
            $sucursales = $request->get("sucursales");
            $rowIndex = $request->get("row");

            
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filename);
            $phpExcelObject->setActiveSheetIndex(0);
            $worksheet = $phpExcelObject->getActiveSheet();
            
            $total=$worksheet->getHighestRow();
            $resultado["total"]=$total;
            $limit = 10;
            $em = $this->getDoctrine()->getManager();
            $i=0;
        
            while ( $i< $limit && $rowIndex <= $total){
                $i++; //solo proceso 100 filas del excell o salgo si llegue al máximo
                $cellA = $worksheet->getCell('A' . $rowIndex);
                $categoriaName= $cellA->getCalculatedValue();
                $categoria = $em->getRepository('BackendAdminBundle:Categoria')->findOneByCode($categoriaName);

                $cellB = $worksheet->getCell('B' . $rowIndex);
                $subcategoriaName= $cellB->getCalculatedValue();
                $subcategoria = $em->getRepository('BackendAdminBundle:Subcategoria')->findOneByCode($subcategoriaName);

                $cellC = $worksheet->getCell('C' . $rowIndex);
                $nameProducto = $cellC->getCalculatedValue();

                $cellD = $worksheet->getCell('D' . $rowIndex);
                $descriptionProducto = $cellD->getCalculatedValue();

                $cellE = $worksheet->getCell('E' . $rowIndex);
                $codeProducto = $cellE->getCalculatedValue();

                $cellF = $worksheet->getCell('F' . $rowIndex);
                $priceProducto = $cellF->getCalculatedValue();

                $cellG = $worksheet->getCell('G' . $rowIndex);
                $disponibleProducto = $cellG->getCalculatedValue();

                $cellH = $worksheet->getCell('H' . $rowIndex);
                $imagenProducto = $cellH->getCalculatedValue();

                $cellI = $worksheet->getCell('I' . $rowIndex);
                $stockProducto = $cellI->getCalculatedValue();

                $cellJ = $worksheet->getCell('J' . $rowIndex);
                $maxVariedadProducto = $cellJ->getCalculatedValue();

                $cellK = $worksheet->getCell('K' . $rowIndex);
                $minVariedadProducto = $cellK->getCalculatedValue();

                $cellL = $worksheet->getCell('L' . $rowIndex);
                $qtyVariedadProducto = $cellL->getCalculatedValue();

                $cellM = $worksheet->getCell('M' . $rowIndex);
                $variedadesProducto = $cellM->getCalculatedValue();
                try{
                        $entity = new Producto();
                        $entity->setName($nameProducto);
                        $entity->setDescription($descriptionProducto);
                        $entity->setCode($codeProducto);
                        $entity->setPrecio($priceProducto);
                        $entity->setAlwaysAvailable($disponibleProducto);
                        $entity->setPath($imagenProducto);
                        $entity->setStock($stockProducto);
                        $entity->setMaxVariedad($maxVariedadProducto);
                        $entity->setMinVariedad($minVariedadProducto);
                        $entity->setQtyVariedad($qtyVariedadProducto);
                        if ($categoria){
                               $entity->setCategoria($categoria);
                        }else{
                            throw new RuntimeException('No existe categoría.');
                        }
                        if ($subcategoria){
                           $entity->setSubcategoria($subcategoria);
                        }else{
                            throw new RuntimeException('No existe subcategoría.');
                        }
                        //agregar sucursales
                        $listSucursales = explode(",",$sucursales);
                        foreach($listSucursales as $s){
                            $sucursal = $em->getRepository('BackendCustomerAdminBundle:Sucursal')->find($s);
                            $entity->addSucursale($sucursal);
                        }
                        //agregar variedades
                        if ($variedadesProducto != ''){
                            $variedades = explode(",",$variedadesProducto);
                            foreach($variedades as $v){
                                //busco la variedad en la tabla por el nombre
                                $variedad = $em->getRepository('BackendCustomerAdminBundle:Variedad')->findOneByName($v);
                                if ($variedad){ //si esta asocio directamente con el producto
                                    $entity->addVariedade($variedad);
                                }else{ //debo crear primero la variedad y dps asociarla con el producto
                                    $variedad = new Variedad();
                                    $variedad->setName($v);
                                    $em->persist($variedad);
                                    $em->flush();
                                    $entity->addVariedade($variedad);
                                }
                            }
                        }
                        $em->persist($entity);
                        $em->flush();

                }catch(RuntimeException $e){
                        $resultado["error"] .= "No se pudo subir el ".$nameProducto.": ".$e->getMessage()."<br>";
                } 

                $rowIndex ++;
            } 


           
      $resultado["row"]=$rowIndex;

      $response = new Response(json_encode($resultado));
       
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;


        

    }

    /*
     *  Genera listado de productos completo en Excel
     *
     */
    
     public function exportarAction(Request $request)
    {
     if ( $this->get('security.context')->isGranted('ROLE_VIEWPRODUCTO')) {

         $em = $this->getDoctrine()->getManager();
       
        $search=$this->generateSQL($request->query->get("search-query")); 
           
       
        $query = $em->createQuery($search);
        
        $excelService = $this->get('phpexcel')->createPHPExcelObject();
                         
                            
        $excelService->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Código')
                    ->setCellValue('B1', 'Nombre')
                    ->setCellValue('C1', 'Descripción')
                    ->setCellValue('D1', 'Precio')
                    ->setCellValue('E1', 'Categoría')
                    ->setCellValue('F1', 'Subcategoría')
                    ->setCellValue('G1', 'Variedades')
                    ->setCellValue('H1', 'Publicado')
                    ->setCellValue('I1', 'Siempre disponible')
                    ->setCellValue('J1', 'Sucursales')
                    ;
                    
        $resultados=$query->getResult();
        $i=2;
        foreach($resultados as $r)
        {   $subcategoria='';
            if ($r->getSubcategoria()){
               $subcategoria=$r->getSubcategoria()->getName();
            }
            $variedades='';
            $separador='';
            foreach($r->getVariedades() as $v){
              $variedades .=$separador.$v->getName();
              $separador=' - ';
            }
            $publicado="NO";
            if ($r->getIsActive()){
              $publicado="SI";
            }
            $disponible="NO";
            if ($r->getAlwaysAvailable()){
              $disponible="SI";
            
            }
            $sucursales='';
            $separador='';
            foreach($r->getSucursales() as $s){
              $sucursales .=$separador.$s->getName();
              $separador=' - ';
            }
            
           $excelService->setActiveSheetIndex(0)
                         ->setCellValue("A$i",$r->getCode())
                         ->setCellValue("B$i",$r->getName())
                         ->setCellValue("C$i",$r->getDescription())
                         ->setCellValue("D$i",$r->getPrecio())
                         ->setCellValue("E$i",$r->getCategoria()->getName())
                         ->setCellValue("F$i",$subcategoria)
                         ->setCellValue("G$i",$variedades)
                         ->setCellValue("H$i",$publicado)
                         ->setCellValue("I$i",$disponible)
                         ->setCellValue("J$i",$sucursales)
                         ;
          $i++;
        }
                            
        $excelService->getActiveSheet()->setTitle('Listado de Productos');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->setActiveSheetIndex(0);
        $excelService->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        
        $fileName="productos_".date("Ymd").".xls";
        //create the response
        $writer = $this->get('phpexcel')->createWriter($excelService, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
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

    public function exportarCategoriasAction(Request $request)
    {
        
        $excelService = $this->get('phpexcel')->createPHPExcelObject();
                         
                            
        $excelService->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nombre')
                    ->setCellValue('B1', 'Código')
                    ;
                    
        $resultados= $this->getDoctrine()->getRepository('BackendAdminBundle:Categoria')->findBy(array(),array("name"=>"asc"));
        $i=2;
        foreach($resultados as $r)
        {
           $excelService->setActiveSheetIndex(0)
                         ->setCellValue("A$i",$r->getName())
                         ->setCellValue("B$i",$r->getCode())
                         ;
          $i++;
        }
                            
        $excelService->getActiveSheet()->setTitle('Listado de Categorias');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->setActiveSheetIndex(0);
        $excelService->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        
        $fileName="categorias_".date("Ymd").".xls";
        //create the response
        $writer = $this->get('phpexcel')->createWriter($excelService, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        //$response->headers->set('Content-Disposition', 'filename='.$fileName);
        echo header("Content-Disposition: attachment; filename=$fileName");
        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->sendHeaders();
        return $response; 
        
        
       
    }

    public function exportarSubcategoriasAction(Request $request)
    {
     
        
        $excelService = $this->get('phpexcel')->createPHPExcelObject();
                         
                            
        $excelService->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Categoría')
                    ->setCellValue('B1', 'Nombre')
                    ->setCellValue('C1', 'Código')
                    
                    ;
                    
        $resultados= $this->getDoctrine()->getRepository('BackendAdminBundle:Subcategoria')->findBy(array(),array("name"=>"asc"));
        $i=2;
        foreach($resultados as $r)
        {
           $excelService->setActiveSheetIndex(0)
                         ->setCellValue("A$i",$r->getCategoria()->getName())
                         ->setCellValue("B$i",$r->getName())
                         ->setCellValue("C$i",$r->getCode())
                         ;
          $i++;
        }
                            
        $excelService->getActiveSheet()->setTitle('Listado de Subcategorías');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $excelService->setActiveSheetIndex(0);
        $excelService->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excelService->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
        
        $fileName="subcategorias_".date("Ymd").".xls";
        $writer = $this->get('phpexcel')->createWriter($excelService, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        //$response->headers->set('Content-Disposition', 'filename='.$fileName);
        echo header("Content-Disposition: attachment; filename=$fileName");
        // If you are using a https connection, you have to set those two headers and use sendHeaders() for compatibility with IE <9
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->sendHeaders();
        return $response; 
        
        
       
    }
    
    /**** EDICION MASIVA DE PRODUCTOS  */

    public function edicionMasivaAction(Request $request){

        if ( $this->get('security.context')->isGranted('ROLE_EDICION')) {

            $user=$this->getUser();
            $em = $this->getDoctrine()->getManager();

            
            if ($request->getMethod() == 'POST') {
              
              $excel='';
              $images='';
              $continuar=true;
             
             
             
             $excel = $request->files->get('excell');
             if (null === $excel){
                $continuar=false;
                $this->get('session')->getFlashBag()->add('error' , 'No se indico un archivo de productos.');
             }else{
                 $path= pathinfo($excel->getClientOriginalName());
                 $ext = $path["extension"];
                 $size = $excel->getClientSize();
                 echo "producto extension $ext size $size maximo".$excel->getMaxFilesize();
                 if ($ext == "xls" || $ext == "xlsx"){
                    if ($size < $excel->getMaxFilesize()){
                        $filename = sha1(uniqid(mt_rand(), true));
                        $dir = __DIR__.'/../../../../web/uploads/importar/'.$user->getId();
                        $excel->move($dir, $filename);
                    }else{
                       $continuar=false;
                       $this->get('session')->getFlashBag()->add('error' , 'El archivo de productos es demasiado grande.'); 
                    }
                }else{
                    $continuar=false;
                    $this->get('session')->getFlashBag()->add('error' , 'El archivo de productos no tiene extensión xls o xlsx.');
                }
             }

             $images = $request->files->get('images');
             if (null !== $images){
                 $path = pathinfo($images->getClientOriginalName());
                 $ext = $path["extension"];
                 $size = $images->getClientSize();
                 echo "imagen extension $ext size $size maximo".$images->getMaxFilesize();
                 if ($ext == "zip" ){
                    if ($size < $images->getMaxFilesize()){
                         $filenameImg = sha1(uniqid(mt_rand(), true)).".zip";
                         $dir = __DIR__.'/../../../../web/uploads/importar/'.$user->getId();
                         $images->move($dir, $filenameImg);
                         $targetzip = $dir."/".$filenameImg;
                         $targetdir = __DIR__.'/../../../../web/uploads/productos/'.$user->getId(); //poner los productos en carpetas por usuario
                         $zip = new ZipArchive();
                         
                         $x = $zip->open($targetzip);  // open the zip file to extract
                         if ($x === true) {
                                $zip->extractTo($targetdir); // place in the directory with same name  
                                $zip->close();
                     
                                unlink($targetzip);
                        }else{
                            $continuar=false;
                            $this->get('session')->getFlashBag()->add('error' , 'El archivo de images no pudo descomprimirse. Verifique que este bien formado.'); 
                        }
                    }else{
                        $continuar=false;
                        $this->get('session')->getFlashBag()->add('error' , 'El archivo de images es demasiado grande.');  
                    }
               } else{
                    $continuar=false;
                    $this->get('session')->getFlashBag()->add('error' , 'El archivo de imagenes no tiene extensión zip .');
               }
             }

             if ($continuar){

                    return $this->render('BackendCustomerAdminBundle:Producto:edicion_importar.html.twig',array("filename"=>$dir."/".$filename));
             }
            }
            
            return $this->render('BackendCustomerAdminBundle:Producto:edicion_masiva.html.twig', array(
                
                    ));

            } else{
                throw new AccessDeniedException();
            }
    }


     public function edicionExcellAction(Request $request){
            $user=$this->getUser();
            $resultado=array("status"=>0,"message"=>'', "total"=>0, "row"=>2, "error"=>'');
            $filename =$request->get("filename");
           
            $rowIndex = $request->get("row");

            
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filename);
            $phpExcelObject->setActiveSheetIndex(0);
            $worksheet = $phpExcelObject->getActiveSheet();
            
            $total=$worksheet->getHighestRow();
            $resultado["total"]=$total;
            $limit = 10;
            $em = $this->getDoctrine()->getManager();
            $i=0;
        
            while ( $i< $limit && $rowIndex <= $total){
                $i++; //solo proceso 100 filas del excell o salgo si llegue al máximo
                $cellA = $worksheet->getCell('A' . $rowIndex);
                $categoriaName= $cellA->getCalculatedValue();
                $categoria = $em->getRepository('BackendAdminBundle:Categoria')->findOneByCode($categoriaName);

                $cellB = $worksheet->getCell('B' . $rowIndex);
                $subcategoriaName= $cellB->getCalculatedValue();
                $subcategoria = $em->getRepository('BackendAdminBundle:Subcategoria')->findOneByCode($subcategoriaName);

                $cellC = $worksheet->getCell('C' . $rowIndex);
                $nameProducto = $cellC->getCalculatedValue();

                $cellD = $worksheet->getCell('D' . $rowIndex);
                $descriptionProducto = $cellD->getCalculatedValue();

                $cellE = $worksheet->getCell('E' . $rowIndex);
                $codeProducto = $cellE->getCalculatedValue();

                $cellF = $worksheet->getCell('F' . $rowIndex);
                $priceProducto = $cellF->getCalculatedValue();

                $cellG = $worksheet->getCell('G' . $rowIndex);
                $disponibleProducto = $cellG->getCalculatedValue();

                $cellH = $worksheet->getCell('H' . $rowIndex);
                $imagenProducto = $cellH->getCalculatedValue();

                $cellI = $worksheet->getCell('I' . $rowIndex);
                $stockProducto = $cellI->getCalculatedValue();

                $cellJ = $worksheet->getCell('J' . $rowIndex);
                $maxVariedadProducto = $cellJ->getCalculatedValue();

                $cellK = $worksheet->getCell('K' . $rowIndex);
                $minVariedadProducto = $cellK->getCalculatedValue();

                $cellL = $worksheet->getCell('L' . $rowIndex);
                $qtyVariedadProducto = $cellL->getCalculatedValue();

                $cellM = $worksheet->getCell('M' . $rowIndex);
                $variedadesProducto = $cellM->getCalculatedValue();
                try{
                        $entity = $em->getRepository('BackendCustomerAdminBundle:Producto')->findOneByCode($codeProducto);

                        if ($entity){
                                $entity->setName($nameProducto);
                                $entity->setDescription($descriptionProducto);
                                $entity->setCode($codeProducto);
                                $entity->setPrecio($priceProducto);
                                $entity->setAlwaysAvailable($disponibleProducto);
                                $entity->setPath($imagenProducto);
                                $entity->setStock($stockProducto);
                                $entity->setMaxVariedad($maxVariedadProducto);
                                $entity->setMinVariedad($minVariedadProducto);
                                $entity->setQtyVariedad($qtyVariedadProducto);
                                if ($categoria){
                                    $entity->setCategoria($categoria);
                                }else{
                                    throw new RuntimeException('No existe categoría.');
                                }
                                if ($subcategoria){
                                   $entity->setSubcategoria($subcategoria);
                                }else{
                                    throw new RuntimeException('No existe subcategoría.');
                                }
                                $listVariedades = $entity->getVariedades();
                                foreach($listVariedades as $l){
                                    $entity->removeVariedade($l);
                                }
                                //agregar variedades
                                $em->persist($entity);
                                $em->flush();

                                if ($variedadesProducto != ''){
                                    $variedades = explode(",",$variedadesProducto);
                                    foreach($variedades as $v){
                                        
                                        //busco la variedad en la tabla por el nombre
                                        $variedad = $em->getRepository('BackendCustomerAdminBundle:Variedad')->findOneByName($v);
                                        if ($variedad){ //si esta asocio directamente con el producto
                                               $entity->addVariedade($variedad);

                                        }else{ //debo crear primero la variedad y dps asociarla con el producto
                                            
                                            $variedad = new Variedad();
                                            $variedad->setName($v);
                                            $em->persist($variedad);
                                            $em->flush();
                                            $entity->addVariedade($variedad);
                                        }
                                    }
                                }


                                


                        }else{
                            throw new RuntimeException('No existe el código de producto: '. $codeProducto );
                        }

                        
                        $em->persist($entity);
                        $em->flush();

                }catch(RuntimeException $e){
                        $resultado["error"] .= "No se pudo subir el ".$nameProducto.": ".$e->getMessage()."<br>";
                } 

                $rowIndex ++;
            } 


           
      $resultado["row"]=$rowIndex;

      $response = new Response(json_encode($resultado));
       
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;


        

    }


    
}

<?php

namespace Backend\CustomerAdminBundle\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Backend\CustomerBundle\Form\ProfileType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;

class PrincipalController extends Controller
{
  

    public function indexAction()
    {
         $user = $this->getUser();

        if ($user->getIsComercio() ){
             return $this->redirect($this->generateUrl('pedido'));
        }else{

         return $this->render('BackendCustomerAdminBundle:Principal:index.html.twig');
      }

    }
    
    public function accessAction()
    {
       $this->get('session')->getFlashBag()->add('error' , 'Su usuario no tiene acceso a esta sección.');    
      return $this->redirect($this->generateUrl('customer_login'));
    }
    
    public function blankAction()
    {
           
      return $this->render('BackendCustomerAdminBundle:Principal:blank.html.twig');
    } 
    
    //login for the frontend
    public function loginFrontAction(Request $request){
        
        
        $email=$request->get("email");
        $password=$request->get("password");
        
        $path=$this->generateUrl("frontend_homepage");
        
        if ($request->get("_target_path")){
          $path=$this->generateUrl($request->get("_target_path"));
        }
        $resultado=array("status"=>0,"message"=>'', "redirect"=>'');
        if ($email && $password){
        try{
          
          $em = $this->getDoctrine()->getManager();
          $user=$em->getRepository('BackendCustomerBundle:Customer')->loadUserByUsername($email);
          
          if ( null !== $user && $user->comparePassword($password) ){
                  
                if (!$user->getIsActive()){
                     
                     $resultado["message"]='Usuario inhabilitado.';
                     $resultado["status"]=0;
                }else{  
                  $this->get('session')->set("user",$user);
                  $token = new UsernamePasswordToken($user, null, "customer", $user->getRoles());
                  $this->get("security.context")->setToken($token); //now the user is logged in
                  $session = $this->getRequest()->getSession();
                  $session->set('_security_customer', serialize($token));
                  $session->set('login',true);
                  $session->set("customer",$user);
                  $session->save();
                  
                  $resultado["message"]='Usuario válido.';
                  $resultado["status"]=1;
                  $resultado["redirect"]=$path;
                  
                  $resultado["user"]=array("email"=>$user->getEmail(), "name"=>$user->getName(),"token"=>$token);
               }   
                  
          }else{
                  $resultado["message"]='Usuario y/o clave incorrectas.';
                  $resultado["status"]=0;             
                
          
          } 
           
         }catch(Exception $e){
                 $resultado["message"]='Usuario y/o clave incorrectas.';
                 $resultado["status"]=0; 
                
         }
         
        }else{
          
              $resultado["message"]='Usuario y/o clave incorrectas.';
              $resultado["status"]=0;
              
        }
        
        $response = new Response(json_encode($resultado));
        
       $response->headers->set('Content-Type', 'application/json');
  
       return $response;
    }
    
    
    
    
    
    public function profileAction(Request $request) {
        
        $customer=$this->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendCustomerBundle:Customer')->find($customer->getId());

        if (!$entity) {
            throw $this->createNotFoundException('No se ha encontrado el cliente.');
        }

        $editForm = $this->createForm(new ProfileType(), $entity);
       

      $defaultData = array('message' => 'Type your message here');
      $form = $this->createFormBuilder($defaultData)
         ->add('password', 'repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'No coincide la contraseña.',
                        'options' => array('attr' => array('class' => 'password-field')),
                        'required' => true,
                        'first_options'  => array('label' => 'Contraseña'),
                        'second_options' => array('label' => 'Repetir contraseña'),
                    ))
        
    
     
        ->getForm();

        if ($request->isMethod('POST')) {
          //update del profile
          if ($request->get('action') == 'update')
          {
          try{  
               
             $editForm->bind($request);

             if ($editForm->isValid()) {
               $em->persist($entity);
               $em->flush();
               $this->get('session')->getFlashBag()->add('success' , 'Se ha actualizado el perfil.');
            
             }
             else
                $this->get('session')->getFlashBag()->add('error' , 'No se ha podido modificar su perfil.');
             
           }
           catch(\Exception $e){
           
             $this->get('session')->getFlashBag()->add('error' , 'No se ha podido modificar su perfil.');
           }  
             
          }
          //change es para cambiar la contraseña
          if ($request->get('action') == 'change')
          {  
             $form->bind($request);
            // data is an array with "password", "confirm" as keys
            
            if ($form->isValid()){
                $data = $form->getData();
                $entity->setPassword($data["password"]);
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success' , 'Se ha cambiado su contraseña.');
            }
            else
                $this->get('session')->getFlashBag()->add('error' , 'No se ha podido modificar su contraseña.');
          }  
        }
        
        return $this->render('BackendCustomerBundle:Customer:profile.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'pass'   => $form->createView()
            
        ));
        
        
        
    }
    
    
}
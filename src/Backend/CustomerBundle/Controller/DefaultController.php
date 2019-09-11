<?php

namespace Backend\CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Backend\CustomerBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Backend\CustomerBundle\Form\Type\CustomerType;

class DefaultController extends Controller {

    public function loginAction() {
       
       if ( $user = $this->getUser())
          return $this->redirect($this->generateUrl('customer_principal'));
        
        $request = $this->getRequest();
        $session = $request->getSession();
        $error='';
        
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
            $this->get('session')->getFlashBag()->add('error' , 'Usuario y/o clave incorrectas.');
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            if ($error)
               $this->get('session')->getFlashBag()->add('error' , 'Usuario y/o clave incorrectas.');
        }
          
        return $this->render(
            'BackendCustomerBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,)
        );
        
    }
    
    

    
    
   

    
    
    
}

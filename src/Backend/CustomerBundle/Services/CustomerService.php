<?php 
namespace Backend\CustomerBundle\Services;
use Backend\CustomerBundle\Entity\Customer;
use Backend\UserBundle\Entity\Group;
use Symfony\Component\HttpFoundation\Response;
class CustomerService
{
    private $em;
    
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
       
    }

    public function login($email,$pass)
    {
          $retorna=array("status"=>1,"message"=>'');
       $entity = $this->em->getRepository('BackendCustomerBundle:Customer')->findOneByEmail($email);
        if ($entity)
         {
         
         if ($entity->getIsActive()){  
             
             
           
                if ( $entity->comparePassword($pass)  )
                   {
                     $retorna["status"]=0;
                     
                   }
                   else     
                       $retorna["message"]="Password no válida";
             
              
       }
       else
              $retorna["message"]="Usuario no activo";    
         }
        else
           $retorna["message"]="Usuario no encontrado";

        
          
      
        return json_encode($retorna);
    }
    
    
    
    
    
    public function validateEmail($email)
    {
      $user = $this->em->getRepository("BackendCustomerBundle:Customer")
                ->findOneBy(array("email"=>$email, "isDelete"=>false));
      if ($user != null)
        return false;
      else
        return true;                 
    
    }
    
    public function validar($usuario)
    {
       $resultado=array("retorna"=>true,"message"=>'');
       
       if ( !$this->validateEmail($usuario["email"]) )
       {
          $resultado["retorna"]=false;
          $resultado["message"]="Ya esta en uso el Email";
          return $resultado;
       }
       
      
    
      
         return $resultado;
    }
    
    public function register($usuario)
    {
     
     $retorna=array("status"=>1,"message"=>'');
     try{ 
      
      $resultado=$this->validar($usuario);
     
             
     if (!$resultado["retorna"])
     {
       $retorna["message"]=$resultado["message"];
     }
     else
     {  
          
         
         $user = new Customer();
         $grupo=$this->em->getRepository('BackendUserBundle:Group')->findOneByRole($usuario["role"]);
         
         
          
           $user->setEmail($usuario["email"]);
           $user->setPassword($usuario["password"]);
           $user->setName($usuario["name"]);
           $user->setLastname($usuario["lastname"]);
           $user->setIsComercio($usuario["isComercio"]);
           $user->setIsActive(true);
           $user->setIsDelete(false);
           $user->setStatus($usuario["status"]);
           $codigo=md5($usuario["email"].rand().date("now"));
           $user->setCodigo($codigo);
           $user->addGroup($grupo);
           
         
         
          $this->em->persist($user);
          $this->em->flush();
         
          $retorna["status"]=0;
          $retorna["message"]="Se ha creado su cuenta de usuario";
          $retorna["codigo"]=$codigo;
          $retorna["usuario"]=$user;
      }
    }
    catch(\Exception $e){
       $retorna["message"]="No se ha podido ingresar usuario";
    }
     return json_encode($retorna);
    }
    
    
    
    
    public function forgotPassword($email)
    {
        $retorna=array("status"=>1,"message"=>'');
      try {
       $q=$this->em->getRepository('BackendCustomerBundle:Customer')
              ->createQueryBuilder('u')
              ->where(' u.email = :email')
              ->setParameter('email', $email) 
              ->getQuery();
     $entity=$q->getOneOrNullResult();         
   
        if ($entity)
        { 
          
          if ($entity->getIsActive())
          {
          
          //genero codigo de seguridad para cambio de contraseña
          $codigo=md5($email.rand().date("now").$entity->getName());
          $entity->setCodigo($codigo);
          $this->em->persist($entity);
          $this->em->flush();
          
          
          
          $retorna["status"]=0;
          $retorna["message"]="Se ha enviado un mail para poder modificar la contraseña";
          $retorna["codigo"]=$codigo;
          $retorna["name"]=$entity->getName();
          $retorna["email"]=$entity->getEmail();
         }
         else
          $retorna["message"]="El usuario no esta activo";
        }
        else{
        
          $retorna["message"]="El usuario no se encontro";
        }
    }
    catch (\Exception $e) {
            $retorna["message"]="No se ha podido encontrar el usuario";
     }
    
     return json_encode($retorna);
        
        
    
    }
    
    
    public function changePassword($cambio)
    {
    $retorna=array("status"=>1,"message"=>'');
    try{
    $entity = $this->em->getRepository('BackendCustomerBundle:Customer')->findOneByCodigo($cambio["codigo"]);
    
    if ($entity)
    { 
      if ($entity->getIsActive())
      {
        $pass=$cambio["password"];
        $entity->setPassword($pass);
        $this->em->persist($entity);
        $this->em->flush();
        //enviar mail con random contraseña 
        $retorna["status"]=0;
        $retorna["password"]=$pass;
        $retorna["name"]=$entity->getName();
        $retorna["email"]=$entity->getEmail();
        $retorna["username"]=$entity->getUsername();
       } 
       else
          $retorna["message"]="El usuario no esta activo";
     
    }
    else{
    
      $retorna["message"]="El usuario no se encontro";
    }
    }
    catch(\Exception $e){
      $retorna["message"]="Error no se pudo ubicar el código";
    }
    
     return json_encode($retorna);
    
    }
    
    
    public function activateAccount($codigo)
    {
    $retorna=array("status"=>1,"message"=>'');
    try{
    $entity = $this->em->getRepository('BackendCustomerBundle:Customer')->findOneByCodigo($codigo);
    
    if ($entity)
    { 
      
        
        $entity->setIsActive(true);
        $entity->setCodigo(''); #el codigo lo borro para que no lo active luego
        $this->em->persist($entity);
        $this->em->flush();
        //enviar mail con random contraseña 
        $retorna["status"]=0;
        $retorna["name"]=$entity->getName();
        $retorna["email"]=$entity->getEmail();
     
    }
    else{
    
      $retorna["message"]="El usuario no se encontro";
    }
    }
    catch(\Exception $e){
      $retorna["message"]="Error no se pudo ubicar el código";
    }
    
     return json_encode($retorna);
    
    }

    
}

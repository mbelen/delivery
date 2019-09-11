<?php

namespace Backend\CustomerBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="tipo_dni")
 * @ORM\Entity()
 */

class TipoDni {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;   

    

    /**
     * @ORM\Column(name="name", type="string",length=100, nullable=true)
     */
    private $name;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Customer", mappedBy="status")
     */
    private $customers;
    
    
    public function __construct() {
       
       
       $this->customers = new ArrayCollection();
       
        
    }

   public function __toString(){
   
         return $this->name;
   }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TipoDni
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add customers
     *
     * @param \Backend\CustomerBundle\Entity\Customer $customers
     * @return TipoDni
     */
    public function addCustomer(\Backend\CustomerBundle\Entity\Customer $customers)
    {
        $this->customers[] = $customers;

        return $this;
    }

    /**
     * Remove customers
     *
     * @param \Backend\CustomerBundle\Entity\Customer $customers
     */
    public function removeCustomer(\Backend\CustomerBundle\Entity\Customer $customers)
    {
        $this->customers->removeElement($customers);
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomers()
    {
        return $this->customers;
    }
}

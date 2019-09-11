<?php

namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="status_pedidos")
 * @ORM\Entity()
 */

class Status {

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
     * @ORM\OneToMany(targetEntity="Proceso", mappedBy="status")
     */
    private $procesos;

    /**
     * @ORM\Column(name="orden",type="integer")
     */

    private $orden;
       
    public function __construct() {
       
    
        $this->procesos = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * @return Status
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
     * @return Status
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

    

    /**
     * Add procesos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Proceso $procesos
     * @return Status
     */
    public function addProceso(\Backend\CustomerAdminBundle\Entity\Proceso $procesos)
    {
        $this->procesos[] = $procesos;

        return $this;
    }

    /**
     * Remove procesos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Proceso $procesos
     */
    public function removeProceso(\Backend\CustomerAdminBundle\Entity\Proceso $procesos)
    {
        $this->procesos->removeElement($procesos);
    }

    /**
     * Get procesos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProcesos()
    {
        return $this->procesos;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     * @return Status
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }
}

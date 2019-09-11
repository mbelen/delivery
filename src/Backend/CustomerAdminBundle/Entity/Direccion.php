<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="direccion")
 * @ORM\Entity()
 */
class Direccion 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="calle", type="string", length=100)
     */
    private $calle;
    
    /**
     * @ORM\Column(name="numero", type="string", length=10)
     */
    private $numero;
	
    /**
     * @ORM\Column(name="piso", type="string",length=4,nullable=true)
     */
    private $piso;
	
    /**
     * @ORM\Column(name="depto", type="string", length=4,nullable=true)
     */
    private $depto;
	
    /**
     * @ORM\Column(name="zip", type="string", length=8, nullable=true)
     */
    private $zip;
	
    /**
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private $isDefault;	
	
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\AdminBundle\Entity\Zona", inversedBy="direcciones")
     * @ORM\JoinColumn(name="zona_id", referencedColumnName="id")
     */
   
    private $zona;
	
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\AdminBundle\Entity\Barrio", inversedBy="direcciones")
     * @ORM\JoinColumn(name="barrio_id", referencedColumnName="id")
     */
   
    private $barrio;
	
    
	 /**
     * @ORM\Column(name="lat", type="string", length=100, nullable=true)
     */
    private $lat;
    
    /**
     * @ORM\Column(name="lon", type="string", length=100, nullable=true)
     */
    private $lon;
	
    /**
     * @ORM\ManyToMany(targetEntity="\Backend\CustomerBundle\Entity\Customer", inversedBy="direcciones")
     */

    protected $customers;
	
    /**
     * @ORM\OneToOne(targetEntity="Sucursal", mappedBy="direccion")
     */
    private $sucursal;
	
    /**
     * @ORM\OneToMany(targetEntity="Pedido", mappedBy="direccion")
     */
    private $pedidos;
	
    /**
     * @ORM\ManyToOne(targetEntity="TipoDireccion", inversedBy="direcciones")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
   
    private $tipo;

		
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
			
    
    public function __construct() {
	
		$this->isDefault = true;
		$this->createdAt = new \DateTime('now');
         
    }
    
    public function __toString()
    {
		$direccion = $this->calle." ".$this->numero;
		  return $direccion;
    }

     public function getFull(){
      $direccion = $this->calle." ".$this->numero;
       if ($this->piso != ''){
         $direccion .=" piso: ".$this->piso;
       }
       
       if ($this->depto != ''){
         $direccion .=" dto: ".$this->depto;
       }
       
       $direccion .= " - ".$this->getBarrio()->getName().", ".$this->getZona()->getName();
      
      return $direccion;
      
     
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
     * Set calle
     *
     * @param string $calle
     * @return Direccion
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get calle
     *
     * @return string 
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Direccion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set piso
     *
     * @param integer $piso
     * @return Direccion
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get piso
     *
     * @return integer 
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set depto
     *
     * @param string $depto
     * @return Direccion
     */
    public function setDepto($depto)
    {
        $this->depto = $depto;

        return $this;
    }

    /**
     * Get depto
     *
     * @return string 
     */
    public function getDepto()
    {
        return $this->depto;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Direccion
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     * @return Direccion
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Direccion
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set zona
     *
     * @param \Backend\AdminBundle\Entity\Zona $zona
     * @return Direccion
     */
    public function setZona(\Backend\AdminBundle\Entity\Zona $zona = null)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \Backend\AdminBundle\Entity\Zona 
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set barrio
     *
     * @param \Backend\AdminBundle\Entity\Barrio $barrio
     * @return Direccion
     */
    public function setBarrio(\Backend\AdminBundle\Entity\Barrio $barrio = null)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return \Backend\AdminBundle\Entity\Barrio 
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Add customers
     *
     * @param \Backend\CustomerBundle\Entity\Customer $customers
     * @return Direccion
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
     * Set tipo
     *
     * @param \Backend\CustomerAdminBundle\Entity\TipoDireccion $tipo
     * @return Direccion
     */
    public function setTipo(\Backend\CustomerAdminBundle\Entity\TipoDireccion $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \Backend\CustomerAdminBundle\Entity\TipoDireccion 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set sucursal
     *
     * @param \Backend\CustomerAdminBundle\Entity\Sucursal $sucursal
     * @return Direccion
     */
    public function setSucursal(\Backend\CustomerAdminBundle\Entity\Sucursal $sucursal = null)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return \Backend\CustomerAdminBundle\Entity\Sucursal 
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    
    /**
     * Add pedidos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Pedido $pedidos
     * @return Direccion
     */
    public function addPedido(\Backend\CustomerAdminBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;

        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Pedido $pedidos
     */
    public function removePedido(\Backend\CustomerAdminBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Direccion
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param string $lon
     * @return Direccion
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return string 
     */
    public function getLon()
    {
        return $this->lon;
    }
}

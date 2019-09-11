<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="pedido")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks 
 */
class Pedido 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;    

    /**
     * @ORM\Column(name="total",type="decimal", scale=2)
     */
    private $total;
	
   /**
     * @ORM\Column(name="pagado", type="boolean")
     */
    private $pagado;
    /**
     * @ORM\ManyToOne(targetEntity="Sucursal", inversedBy="pedidos")
     * @ORM\JoinColumn(name="sucursal_id", referencedColumnName="id")
     */

    protected $sucursal;
	   
	 /**
     * @ORM\OneToMany(targetEntity="Detalle", mappedBy="pedido")
     */
    private $detalles;
   
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\AdminBundle\Entity\PayMethod", inversedBy="pedidos")
     * @ORM\JoinColumn(name="pay_id", referencedColumnName="id")
     */
    private $paymethod;
    
     /**
     * @ORM\OneToMany(targetEntity="Proceso", mappedBy="pedido")
     */
    private $procesos;
	
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\CustomerBundle\Entity\Customer", inversedBy="pedidos")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;	
	
    /**
     * @ORM\ManyToOne(targetEntity="Direccion", inversedBy="pedidos")
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     */
    private $direccion;
		
	
    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
   
     /**
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;	
    

    public function __construct() {
	
		 
		  $this->createdAt = new \DateTime('now');
		  $this->detalles = new \Doctrine\Common\Collections\ArrayCollection();
          $this->procesos = new \Doctrine\Common\Collections\ArrayCollection();
          $pagado = false;
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
     * Set total
     *
     * @param float $total
     * @return Pedido
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set pagado
     *
     * @param boolean $pagado
     * @return Pedido
     */
    public function setPagado($pagado)
    {
        $this->pagado = $pagado;

        return $this;
    }

    /**
     * Get pagado
     *
     * @return boolean 
     */
    public function getPagado()
    {
        return $this->pagado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return Pedido
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Pedido
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
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Pedido
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set sucursal
     *
     * @param \Backend\CustomerAdminBundle\Entity\Sucursal $sucursal
     * @return Pedido
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
     * Add detalles
     *
     * @param \Backend\CustomerAdminBundle\Entity\Detalle $detalles
     * @return Pedido
     */
    public function addDetalle(\Backend\CustomerAdminBundle\Entity\Detalle $detalles)
    {
        $this->detalles[] = $detalles;

        return $this;
    }

    /**
     * Remove detalles
     *
     * @param \Backend\CustomerAdminBundle\Entity\Detalle $detalles
     */
    public function removeDetalle(\Backend\CustomerAdminBundle\Entity\Detalle $detalles)
    {
        $this->detalles->removeElement($detalles);
    }

    /**
     * Get detalles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDetalles()
    {
        return $this->detalles;
    }

    /**
     * Set paymethod
     *
     * @param \Backend\AdminBundle\Entity\PayMethod $paymethod
     * @return Pedido
     */
    public function setPaymethod(\Backend\AdminBundle\Entity\PayMethod $paymethod = null)
    {
        $this->paymethod = $paymethod;

        return $this;
    }

    /**
     * Get paymethod
     *
     * @return \Backend\AdminBundle\Entity\PayMethod 
     */
    public function getPaymethod()
    {
        return $this->paymethod;
    }

    /**
     * Add procesos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Proceso $procesos
     * @return Pedido
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
     * Set customer
     *
     * @param \Backend\CustomerBundle\Entity\Customer $customer
     * @return Pedido
     */
    public function setCustomer(\Backend\CustomerBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Backend\CustomerBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set direccion
     *
     * @param \Backend\CustomerAdminBundle\Entity\Direccion $direccion
     * @return Pedido
     */
    public function setDireccion(\Backend\CustomerAdminBundle\Entity\Direccion $direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return \Backend\CustomerAdminBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @return mixed
     */

    public function getLastProceso(){

        return $this->procesos->last();
    }
}

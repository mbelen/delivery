<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Table(name="proceso")
 * @ORM\Entity()
 *  
 */
class Proceso 
{
    
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;    
    /**
     * @ORM\ManyToOne(targetEntity="Pedido", inversedBy="procesos")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     */
    private $pedido;
    
     /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="procesos")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;
    
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
     * Set comentarios
     *
     * @param string $comentarios
     * @return Proceso
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
     * @return Proceso
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
     * @return Proceso
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
     * Set pedido
     *
     * @param \Backend\CustomerAdminBundle\Entity\Pedido $pedido
     * @return Proceso
     */
    public function setPedido(\Backend\CustomerAdminBundle\Entity\Pedido $pedido = null)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return \Backend\CustomerAdminBundle\Entity\Pedido 
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set status
     *
     * @param \Backend\CustomerAdminBundle\Entity\Status $status
     * @return Proceso
     */
    public function setStatus(\Backend\CustomerAdminBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Backend\CustomerAdminBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }
}

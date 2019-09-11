<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="variedad")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks  
 */
class Variedad 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;
    
    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(name="code", type="string", length=100, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
   
     /**
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;
	
    /**
     * @ORM\ManyToMany(targetEntity="Producto", mappedBy="variedades", cascade = {"persist", "remove"})
     */

    protected $productos;
    
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\CustomerBundle\Entity\Customer", inversedBy="variedades")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
   
    private $customer;
	

    public function __construct() {

		  $this->active = true;
		  $this->createdAt = new \DateTime('now');
		  $this->productos = new \Doctrine\Common\Collections\ArrayCollection();

    }
	
    public function __toString()
    {
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
     * @return Producto
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
     * Set code
     *
     * @param string $code
     * @return Producto
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;

    }
	
    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Producto
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
     * @return Producto
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
     * Set categoria
     *
     * @param \Backend\AdminBundle\Entity\Categoria $categoria
     * @return Producto
     */
    
    /**
     * Set description
     *
     * @param string $description
     * @return Producto
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }


  
    /**
     * Set active
     *
     * @param boolean $active
     * @return Variedad
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add productos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Producto $productos
     * @return Variedad
     */
    public function addProducto(\Backend\CustomerAdminBundle\Entity\Producto $productos)
    {
        //$this->productos[] = $productos;

        //return $this;
         $this->getProductos()->add($productos);
         $productos->getVariedades()->add($this);
    }

    /**
     * Remove productos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Producto $productos
     */
    public function removeProducto(\Backend\CustomerAdminBundle\Entity\Producto $productos)
    {
        $this->productos->removeElement($productos);
        
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductos()
    {
        return $this->productos;
    }

    /**
     * Set customer
     *
     * @param \Backend\CustomerBundle\Entity\Customer $customer
     * @return Variedad
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
}

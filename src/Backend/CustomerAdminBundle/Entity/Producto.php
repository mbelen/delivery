<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="producto")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks  
 */
class Producto 
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
     * @ORM\Column(name="price", type="decimal", scale=2)
     */
    private $precio;

    /**
     * @ORM\Column(name="pricePromo", type="decimal", scale=2, nullable=true)
     */
    private $precioPromo;

    /**
     * @ORM\Column(name="always_available", type="boolean")
     */
    private $alwaysAvailable;    
	
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
   
     /**
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;
	
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="stock", type="integer",nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(name="maxVariedad", type="integer",nullable=true)
     */
    private $maxVariedad;
    
    /**
     * @ORM\Column(name="minVariedad", type="integer",nullable=true)
     */
    private $minVariedad;

    /**
     * @ORM\Column(name="qtyVariedad", type="boolean",nullable=true)
     */
    private $qtyVariedad;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Sucursal", inversedBy="productos", cascade={"persist","remove"})
	 * @ORM\JoinTable(name="sucursal_producto")
     */
    protected $sucursales;
	
    /**
     * @ORM\ManyToMany(targetEntity="Variedad", inversedBy="productos")
	   * @ORM\JoinTable(name="producto_variedad")
     */
    protected $variedades;
	
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\AdminBundle\Entity\Categoria", inversedBy="productos")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;
   
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\AdminBundle\Entity\Subcategoria", inversedBy="productos")
     * @ORM\JoinColumn(name="subcategoria_id", referencedColumnName="id")
     */
    private $subcategoria;

    /**
     * @ORM\ManyToMany(targetEntity="Promocion", mappedBy="productos", cascade={"persist","remove"})
     */
    protected $promociones;

    /**
     * @ORM\ManyToMany(targetEntity="Promocion", mappedBy="productosExcluidos", cascade={"persist","remove"})
     */
    protected $promosExcluidos;

	 /**
     * @ORM\OneToMany(targetEntity="Detalle", mappedBy="producto")
     */
    private $detalles;	
	
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    
    private $path;
    private $temp;
    private $file;
    	
    public function __construct() {

		  $this->alwaysAvailable = true;
		  $this->isActive = true;
		  $this->createdAt = new \DateTime('now');
		  $this->variedades = new \Doctrine\Common\Collections\ArrayCollection();
          $this->minVariedad = 0;
          $this->maxVariedad = 0;
         
    }

    public function __toString()
    {
          return $this->name;
    }
    
    
     /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

     /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            @unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            @unlink($file);
        }
    }
    
    
    
    public function getFile()
    {
        return $this->file;
    }
    

    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }
    
    

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        $sucursales=$this->getSucursales();
        return 'uploads/productos/'.$sucursales[0]->getCustomer()->getId();
    }
    
    
     /**
     * @ORM\PreUpdate()
     * 
     */
     
    public function modifiedUpdate(){
    
      $this->setModifiedAt(new \DateTime('now'));
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
     * Set precio
     *
     * @param string $precio
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set alwaysAvailable
     *
     * @param boolean $alwaysAvailable
     * @return Producto
     */
    public function setAlwaysAvailable($alwaysAvailable)
    {
        $this->alwaysAvailable = $alwaysAvailable;

        return $this;
    }

    /**
     * Get alwaysAvailable
     *
     * @return boolean 
     */
    public function getAlwaysAvailable()
    {
        return $this->alwaysAvailable;
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
    public function setCategoria(\Backend\AdminBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Backend\AdminBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set subcategoria
     *
     * @param \Backend\AdminBundle\Entity\Subcategoria $subcategoria
     * @return Producto
     */
    public function setSubcategoria(\Backend\AdminBundle\Entity\Subcategoria $subcategoria = null)
    {
        $this->subcategoria = $subcategoria;

        return $this;
    }

    /**
     * Get subcategoria
     *
     * @return \Backend\AdminBundle\Entity\Subcategoria 
     */
    public function getSubcategoria()
    {
        return $this->subcategoria;
    }

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
     * Set path
     *
     * @param string $path
     * @return Producto
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;

    }

    /**
     * Add sucursales
     *
     * @param \Backend\CustomerAdminBundle\Entity\Sucursales $sucursales
     * @return Producto
     */
    public function addSucursale(\Backend\CustomerAdminBundle\Entity\Sucursal $sucursales)
    {
        $this->sucursales[] = $sucursales;
		$sucursales->addProducto($this);

        return $this;
    }

    /**
     * Remove sucursales
     *
     * @param \Backend\CustomerAdminBundle\Entity\Sucursales $sucursales
     */
    public function removeSucursale(\Backend\CustomerAdminBundle\Entity\Sucursal $sucursales)
    {
        $this->sucursales->removeElement($sucursales);
		$sucursales->removeProducto($this);
    }

    /**
     * Get sucursales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSucursales()
    {
        return $this->sucursales;
    }

    /**
     * Add variedades
     *
     * @param \Backend\CustomerAdminBundle\Entity\Variedad $variedades
     * @return Producto
     */
    public function addVariedades(\Backend\CustomerAdminBundle\Entity\Variedad $variedades)
    {
        $this->variedades[] = $variedades;

        return $this;
    }

    /**
     * Remove variedades
     *
     * @param \Backend\CustomerAdminBundle\Entity\Variedad $variedades
     */
    public function removeVariedades(\Backend\CustomerAdminBundle\Entity\Variedad $variedades)
    {
        $this->variedades->removeElement($variedades);
    }

    /**
     * Get variedades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVariedades()
    {
        return $this->variedades;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Producto
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    
    /**
     * Add variedades
     *
     * @param \Backend\CustomerAdminBundle\Entity\Variedad $variedades
     * @return Producto
     */
    public function addVariedade(\Backend\CustomerAdminBundle\Entity\Variedad $variedades)
    {
        $this->variedades[] = $variedades;

        return $this;
    }

    /**
     * Remove variedades
     *
     * @param \Backend\CustomerAdminBundle\Entity\Variedad $variedades
     */
    public function removeVariedade(\Backend\CustomerAdminBundle\Entity\Variedad $variedades)
    {
        $this->variedades->removeElement($variedades);
    }

    

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Producto
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add pedidos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Pedido $pedidos
     * @return Producto
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
     * Add detalles
     *
     * @param \Backend\CustomerAdminBundle\Entity\Detalle $detalles
     * @return Producto
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
     * Set maxVariedad
     *
     * @param integer $maxVariedad
     * @return Producto
     */
    public function setMaxVariedad($maxVariedad)
    {
        $this->maxVariedad = $maxVariedad;

        return $this;
    }

    /**
     * Get maxVariedad
     *
     * @return integer 
     */
    public function getMaxVariedad()
    {
        return $this->maxVariedad;
    }

    /**
     * Set qtyVariedad
     *
     * @param integer $qtyVariedad
     * @return Producto
     */
    public function setQtyVariedad($qtyVariedad)
    {
        $this->qtyVariedad = $qtyVariedad;

        return $this;
    }

    /**
     * Get qtyVariedad
     *
     * @return integer 
     */
    public function getQtyVariedad()
    {
        return $this->qtyVariedad;
    }

    /**
     * Set minVariedad
     *
     * @param integer $minVariedad
     * @return Producto
     */
    public function setMinVariedad($minVariedad)
    {
        $this->minVariedad = $minVariedad;

        return $this;
    }

    /**
     * Get minVariedad
     *
     * @return integer 
     */
    public function getMinVariedad()
    {
        return $this->minVariedad;
    }

    /**
     * Add promociones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promociones
     * @return Producto
     */
    public function addPromocione(\Backend\CustomerAdminBundle\Entity\Promocion $promociones)
    {
        $this->promociones[] = $promociones;

        return $this;
    }

    /**
     * Remove promociones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promociones
     */
    public function removePromocione(\Backend\CustomerAdminBundle\Entity\Promocion $promociones)
    {
        $this->promociones->removeElement($promociones);
    }

    /**
     * Get promociones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromociones()
    {
        return $this->promociones;
    }

    /**
     * Add promosExcluidos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promosExcluidos
     * @return Producto
     */
    public function addPromosExcluido(\Backend\CustomerAdminBundle\Entity\Promocion $promosExcluidos)
    {
        $this->promosExcluidos[] = $promosExcluidos;

        return $this;
    }

    /**
     * Remove promosExcluidos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promosExcluidos
     */
    public function removePromosExcluido(\Backend\CustomerAdminBundle\Entity\Promocion $promosExcluidos)
    {
        $this->promosExcluidos->removeElement($promosExcluidos);
    }

    /**
     * Get promosExcluidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromosExcluidos()
    {
        return $this->promosExcluidos;
    }

    /**
     * Set precioPromo
     *
     * @param string $precioPromo
     * @return Producto
     */
    public function setPrecioPromo($precioPromo)
    {
        $this->precioPromo = $precioPromo;

        return $this;
    }

    /**
     * Get precioPromo
     *
     * @return string 
     */
    public function getPrecioPromo()
    {
        return $this->precioPromo;
    }

}

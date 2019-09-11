<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="sucursal")
 * @ORM\Entity()
 * @UniqueEntity("direccion")
 * @ORM\HasLifecycleCallbacks  
 */
class Sucursal 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(name="phone", type="string", length=100)
     */
    private $phone;
	
    /**
     * @ORM\Column(name="email", type="string", length=100,nullable=true)
     */
    private $email;
	
    /**
     * @ORM\Column(name="website", type="string", length=100,nullable=true)
     */
    private $website;
	
    /**
     * @ORM\Column(name="cuit", type="string", length=100)
     */
    private $cuit;
    
    /**
     * @ORM\Column(name="radio", type="integer")
     */
    private $radio; 
  	
	
    /**
     * @ORM\Column(name="open", type="boolean",nullable=true)
     */
    private $open;	
	
    /**
     * @ORM\Column(name="premium", type="boolean",nullable=true)
     */
    private $premium;	
	
    /**
     * @ORM\ManyToOne(targetEntity="\Backend\CustomerBundle\Entity\Customer", inversedBy="sucursales")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
   
    private $customer;	
	
    /**
     * @ORM\OneToOne(targetEntity="Direccion", inversedBy="sucursal")
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     */
   
    private $direccion;	

    /**
     * @ORM\OneToMany(targetEntity="Region", mappedBy="sucursal")
     */
   
    private $regiones; 
	
    /**
     * @ORM\ManyToMany(targetEntity="\Backend\AdminBundle\Entity\Categoria", inversedBy="sucursales")
     */
    protected $categorias;
    
    /**
     * @ORM\ManyToMany(targetEntity="\Backend\AdminBundle\Entity\Subcategoria", inversedBy="sucursales")
     */
    protected $subcategorias;
	
    /**
     * @ORM\ManyToMany(targetEntity="Producto", mappedBy="sucursales", cascade={"persist","remove"})
     */
    protected $productos;

    /**
     * @ORM\ManyToMany(targetEntity="Promocion", mappedBy="sucursales", cascade={"persist","remove"})
     */
    protected $promociones;

    /**
     * @ORM\ManyToMany(targetEntity="\Backend\AdminBundle\Entity\PayMethod", inversedBy="sucursales")
	 * @ORM\JoinTable(name="paymethod_sucursal")
     */
    protected $paymethods;	
	
    /**
    * @ORM\ManyToMany(targetEntity="\Backend\AdminBundle\Entity\Horario", inversedBy="sucursales")
	* @ORM\JoinTable(name="sucursal_horario")
    */
	
    private $horarios;

  
    /**
     * @ORM\OneToMany(targetEntity="Pedido", mappedBy="sucursal")
     */
    private $pedidos;
		
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
	
    private $createdAt;
    
    /**
     * @ORM\Column(name="modified_at", type="datetime")
     */
	
    private $modifiedAt;
	
    /**
     * @ORM\Column(name="is_active", type="boolean",nullable=true)
     */
    private $is_active;
		
     /**
     * @ORM\OneToMany(targetEntity="\Backend\CustomerAdminBundle\Entity\Favorito", mappedBy="sucursal")
     */
    private $favoritos; 
	
    /**
     * costo del envio    
     * @ORM\Column(name="costo", type="decimal", scale=2, nullable=true)
     */
	
	private $delivery;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
	
	private $tiempo_entrega;
	
    /**
     * @ORM\Column(name="minimo", type="decimal", scale=2, nullable=true)
     */
	
	private $minimo;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="sucursal")
     */
    private $banners;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    private $header;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    
    /**
     * @ORM\Column(name="show_direccion", type="boolean", nullable=true)
     */
    private $show_direccion;	

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    
    private $path;
    private $temp;
    private $file;
     

    
    public function __construct() {
	
        $this->createdAt = new \DateTime('now');
        $this->modifiedAt = new \DateTime('now');
        $this->open = false;
        $this->active = true;
        $this->is_premium = false;
        $this->radio = 0;
        $this->minimo = 0;
        $this->delivery = 0;
        $this->productos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->regiones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->horarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subcategorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->favoritos =  new ArrayCollection();     
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
        return 'uploads/sucursales';
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
     * Set phone
     *
     * @param string $phone
     * @return Sucursal
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     * @return Sucursal
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string 
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set is_unica
     *
     * @param boolean $isUnica
     * @return Sucursal
     */
    public function setIsUnica($isUnica)
    {
        $this->is_unica = $isUnica;

        return $this;
    }

    /**
     * Get is_unica
     *
     * @return boolean 
     */
    public function getIsUnica()
    {
        return $this->is_unica;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Sucursal
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Sucursal
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set customer
     *
     * @param \Backend\CustomerBundle\Entity\Customer $customer
     * @return Sucursal
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
     * Set zona
     *
     * @param \Backend\AdminBundle\Entity\Zona $zona
     * @return Sucursal
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
     * @return Sucursal
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
     * Set direccion
     *
     * @param \Backend\CustomerAdminBundle\Entity\Direccion $direccion
     * @return Sucursal
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
     * Add categorias
     *
     * @param \Backend\AdminBundle\Entity\Categoria $categorias
     * @return Sucursal
     */
    public function addCategoria(\Backend\AdminBundle\Entity\Categoria $categorias)
    {
        $this->categorias[] = $categorias;

        return $this;
    }

    /**
     * Remove categorias
     *
     * @param \Backend\AdminBundle\Entity\Categoria $categorias
     */
    public function removeCategoria(\Backend\AdminBundle\Entity\Categoria $categorias)
    {
        $this->categorias->removeElement($categorias);
    }

    /**
     * Get categorias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Sucursal
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
     * Set open
     *
     * @param boolean $open
     * @return Sucursal
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return boolean 
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Sucursal
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Sucursal
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Add productos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Producto $productos
     * @return Sucursal
     */
    public function addProducto(\Backend\CustomerAdminBundle\Entity\Producto $productos)
    {
        $this->productos[] = $productos;

        return $this;
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
     * Add horarios
     *
     * @param \Backend\AdminBundle\Entity\Horario $horarios
     * @return Sucursal
     */
    public function addHorario(\Backend\AdminBundle\Entity\Horario $horarios)
    {
        $this->horarios[] = $horarios;

        return $this;
    }

    /**
     * Remove horarios
     *
     * @param \Backend\AdminBundle\Entity\Horario $horarios
     */
    public function removeHorario(\Backend\AdminBundle\Entity\Horario $horarios)
    {
        $this->horarios->removeElement($horarios);
    }

    /**
     * Get horarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHorarios()
    {
        return $this->horarios;
    }

    /**
     * Add paymethods
     *
     * @param \Backend\CustomerAdminBundle\Entity\PayMethod $paymethods
     * @return Sucursal
     */
    public function addPaymethod(\Backend\AdminBundle\Entity\PayMethod $paymethods)
    {
        $this->paymethods[] = $paymethods;

        return $this;
    }

    /**
     * Remove paymethods
     *
     * @param \Backend\CustomerAdminBundle\Entity\PayMethod $paymethods
     */
    public function removePaymethod(\Backend\AdminBundle\Entity\PayMethod $paymethods)
    {
        $this->paymethods->removeElement($paymethods);
    }

    /**
     * Get paymethods
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPaymethods()
    {
        return $this->paymethods;
    }

    /**
     * Add pedidos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Pedido $pedidos
     * @return Sucursal
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
     * Add favoritos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Favorito $favoritos
     * @return Sucursal
     */
    public function addFavorito(\Backend\CustomerAdminBundle\Entity\Favorito $favoritos)
    {
        $this->favoritos[] = $favoritos;

        return $this;
    }

    /**
     * Remove favoritos
     *
     * @param \Backend\CustomerAdminBundle\Entity\Favorito $favoritos
     */
    public function removeFavorito(\Backend\CustomerAdminBundle\Entity\Favorito $favoritos)
    {
        $this->favoritos->removeElement($favoritos);
    }

    /**
     * Get favoritos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFavoritos()
    {
        return $this->favoritos;
    }

 

    /**
     * Set premium
     *
     * @param boolean $premium
     * @return Sucursal
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * Get premium
     *
     * @return boolean 
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Sucursal
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
     * Add subcategorias
     *
     * @param \Backend\AdminBundle\Entity\Subcategoria $subcategorias
     * @return Sucursal
     */
    public function addSubcategoria(\Backend\AdminBundle\Entity\Subcategoria $subcategorias)
    {
        $this->subcategorias[] = $subcategorias;

        return $this;
    }

    /**
     * Remove subcategorias
     *
     * @param \Backend\AdminBundle\Entity\Subcategoria $subcategorias
     */
    public function removeSubcategoria(\Backend\AdminBundle\Entity\Subcategoria $subcategorias)
    {
        $this->subcategorias->removeElement($subcategorias);
    }

    /**
     * Get subcategorias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubcategorias()
    {
        return $this->subcategorias;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Sucursal
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
     * Set radio
     *
     * @param integer $radio
     * @return Sucursal
     */
    public function setRadio($radio)
    {
        $this->radio = $radio;

        return $this;
    }

    /**
     * Get radio
     *
     * @return integer 
     */
    public function getRadio()
    {
        return $this->radio;
    }

    /**
     * Set delivery
     *
     * @param string $delivery
     * @return Sucursal
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return string 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set minimo
     *
     * @param string $minimo
     * @return Sucursal
     */
    public function setMinimo($minimo)
    {
        $this->minimo = $minimo;

        return $this;
    }

    /**
     * Get minimo
     *
     * @return string 
     */
    public function getMinimo()
    {
        return $this->minimo;
    }

    /**
     * Set tiempo_entrega
     *
     * @param string $tiempoEntrega
     * @return Sucursal
     */
    public function setTiempoEntrega($tiempoEntrega)
    {
        $this->tiempo_entrega = $tiempoEntrega;

        return $this;
    }

    /**
     * Get tiempo_entrega
     *
     * @return string 
     */
    public function getTiempoEntrega()
    {
        return $this->tiempo_entrega;
    }

    /**
     * Set headerPath
     *
     * @param string $headerPath
     * @return Sucursal
     */
    public function setHeaderPath($headerPath)
    {
        $this->headerPath = $headerPath;

        return $this;
    }

    /**
     * Get headerPath
     *
     * @return string 
     */
    public function getHeaderPath()
    {
        return $this->headerPath;
    }

    /**
     * Set header
     *
     * @param string $header
     * @return Sucursal
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string 
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Add promociones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promociones
     * @return Sucursal
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
     * Add banners
     *
     * @param \Backend\CustomerAdminBundle\Entity\Banner $banners
     * @return Sucursal
     */
    public function addBanner(\Backend\CustomerAdminBundle\Entity\Banner $banners)
    {
        $this->banners[] = $banners;

        return $this;
    }

    /**
     * Remove banners
     *
     * @param \Backend\CustomerAdminBundle\Entity\Banner $banners
     */
    public function removeBanner(\Backend\CustomerAdminBundle\Entity\Banner $banners)
    {
        $this->banners->removeElement($banners);
    }

    /**
     * Get banners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBanners()
    {
        return $this->banners;
    }

    

    /**
     * Add regiones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Region $regiones
     * @return Sucursal
     */
    public function addRegione(\Backend\CustomerAdminBundle\Entity\Region $regiones)
    {
        $this->regiones[] = $regiones;

        return $this;
    }

    /**
     * Remove regiones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Region $regiones
     */
    public function removeRegione(\Backend\CustomerAdminBundle\Entity\Region $regiones)
    {
        $this->regiones->removeElement($regiones);
    }

    /**
     * Get regiones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegiones()
    {
        return $this->regiones;
    }

    /**
     * Set show_direccion
     *
     * @param boolean $showDireccion
     * @return Sucursal
     */
    public function setShowDireccion($showDireccion)
    {
        $this->show_direccion = $showDireccion;

        return $this;
    }

    /**
     * Get show_direccion
     *
     * @return boolean 
     */
    public function getShowDireccion()
    {
        return $this->show_direccion;
    }
}

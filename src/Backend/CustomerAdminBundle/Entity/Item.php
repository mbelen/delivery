<?php 

namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="item")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks  
 */
class Item 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="items")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    
    private $producto;
    
    /**
     * @ORM\ManyToOne(targetEntity="Promocion", inversedBy="items")
     * @ORM\JoinColumn(name="promocion_id", referencedColumnName="id")
     */
    
    private $promocion;
    
    /**
     * @ORM\OneToMany(targetEntity="Detalle", mappedBy="item")
     */
    private $detalles;	
    
    /**
     * @ORM\Column(name="price", type="decimal", scale=2)
     */
    private $precio;
    
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
     * Set precio
     *
     * @param string $precio
     * @return Item
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
     * Set producto
     *
     * @param \Backend\CustomerAdminBundle\Entity\Producto $producto
     * @return Item
     */
    public function setProducto(\Backend\CustomerAdminBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \Backend\CustomerAdminBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set promocion
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promocion
     * @return Item
     */
    public function setPromocion(\Backend\CustomerAdminBundle\Entity\Promocion $promocion = null)
    {
        $this->promocion = $promocion;

        return $this;
    }

    /**
     * Get promocion
     *
     * @return \Backend\CustomerAdminBundle\Entity\Promocion 
     */
    public function getPromocion()
    {
        return $this->promocion;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detalles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add detalles
     *
     * @param \Backend\CustomerAdminBundle\Entity\Detalle $detalles
     * @return Item
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
}

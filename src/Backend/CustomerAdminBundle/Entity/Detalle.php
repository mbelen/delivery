<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="detalle")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks  
 */
class Detalle 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="price", type="decimal", scale=2)
     */
    private $precio;  //precio unitario

	/**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;
    
    /**
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="detalles")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    private $producto;
    
    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="detalles")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pedido", inversedBy="detalles")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     */
    private $pedido;

    /**
     * @ORM\Column(name="variedades", type="text", nullable=true)
     */

    private $variedades;
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
     * @return Detalle
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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Detalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set producto
     *
     * @param \Backend\CustomerAdminBundle\Entity\Producto $producto
     * @return Detalle
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
     * Set pedido
     *
     * @param \Backend\CustomerAdminBundle\Entity\Pedido $pedido
     * @return Detalle
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
     * Set variedades
     *
     * @param string $variedades
     * @return Detalle
     */
    public function setVariedades($variedades)
    {
        $this->variedades = $variedades;

        return $this;
    }

    /**
     * Get variedades
     *
     * @return string 
     */
    public function getVariedades()
    {
        return $this->variedades;
    }

    /**
     * Set item
     *
     * @param \Backend\CustomerAdminBundle\Entity\Item $item
     * @return Detalle
     */
    public function setItem(\Backend\CustomerAdminBundle\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \Backend\CustomerAdminBundle\Entity\Item 
     */
    public function getItem()
    {
        return $this->item;
    }
}

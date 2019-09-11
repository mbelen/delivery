<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="region")
 * @ORM\Entity()
 * 
 *    
 */
class Region 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *        
     */
    private $id;
    
     /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Coordenada", mappedBy="region")
     */
    private $coordenadas;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sucursal", inversedBy="regiones")
     * @ORM\JoinColumn(name="sucursal_id", referencedColumnName="id")
     */
    private $sucursal;
    
    public function __construct() {
	
	       $this->coordenadas = new ArrayCollection(); 
         
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
     * @return Region
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
     * Add coordenadas
     *
     * @param \Backend\CustomerAdminBundle\Entity\Coordenada $coordenadas
     * @return Region
     */
    public function addCoordenada(\Backend\CustomerAdminBundle\Entity\Coordenada $coordenadas)
    {
        $this->coordenadas[] = $coordenadas;

        return $this;
    }

    /**
     * Remove coordenadas
     *
     * @param \Backend\CustomerAdminBundle\Entity\Coordenada $coordenadas
     */
    public function removeCoordenada(\Backend\CustomerAdminBundle\Entity\Coordenada $coordenadas)
    {
        $this->coordenadas->removeElement($coordenadas);
    }

    /**
     * Get coordenadas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }

    /**
     * Set sucursal
     *
     * @param \Backend\CustomerAdminBundle\Entity\Sucursal $sucursal
     * @return Region
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
}

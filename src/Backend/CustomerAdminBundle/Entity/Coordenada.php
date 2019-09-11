<?php
 
namespace Backend\CustomerAdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="coordenada")
 * @ORM\Entity()
 *
 *    
 */
class Coordenada 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *        
     */
    private $id;
    
    /**
     * @ORM\Column(name="lat", type="string", length=100, nullable=true)
     */
    private $lat;
    
    /**
     * @ORM\Column(name="lng", type="string", length=100, nullable=true)
     */
    private $lng;
    
    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="coordenadas")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;
    
    


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
     * Set lat
     *
     * @param string $lat
     * @return Coordenada
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
     * Set lng
     *
     * @param string $lng
     * @return Coordenada
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set region
     *
     * @param \Backend\CustomerAdminBundle\Entity\Region $region
     * @return Coordenada
     */
    public function setRegion(\Backend\CustomerAdminBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Backend\CustomerAdminBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }
}

<?php

namespace Backend\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="horario_promo")
 * @ORM\Entity()
 */
class HorarioPromo
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="all_day", type="boolean",nullable=true)
     */

    private $allDay;

    /**
     * @ORM\Column(name="desde", type="string", length=100,nullable=true)
     */

    private $desde;

    /**
     * @ORM\Column(name="hasta", type="string", length=100,nullable=true)
     */

    private $hasta;

    /**
     * @ORM\ManyToMany(targetEntity="\Backend\CustomerAdminBundle\Entity\Promocion", mappedBy="horarios")
     */

    private $promociones;

    /**
     * @ORM\ManyToOne(targetEntity="Dia", inversedBy="horarios_promos")
     * @ORM\JoinColumn(name="dia_id", referencedColumnName="id")
     */

    protected $dia;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sucursales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->allDay = false;

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
     * Set allDay
     *
     * @param boolean $allDay
     * @return HorarioPromo
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;

        return $this;
    }

    /**
     * Get allDay
     *
     * @return boolean 
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * Set desde
     *
     * @param string $desde
     * @return HorarioPromo
     */
    public function setDesde($desde)
    {
        $this->desde = $desde;

        return $this;
    }

    /**
     * Get desde
     *
     * @return string 
     */
    public function getDesde()
    {
        return $this->desde;
    }

    /**
     * Set hasta
     *
     * @param string $hasta
     * @return HorarioPromo
     */
    public function setHasta($hasta)
    {
        $this->hasta = $hasta;

        return $this;
    }

    /**
     * Get hasta
     *
     * @return string 
     */
    public function getHasta()
    {
        return $this->hasta;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return HorarioPromo
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
     * Add promociones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promociones
     * @return HorarioPromo
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
     * Set dia
     *
     * @param \Backend\AdminBundle\Entity\Dia $dia
     * @return HorarioPromo
     */
    public function setDia(\Backend\AdminBundle\Entity\Dia $dia = null)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \Backend\AdminBundle\Entity\Dia 
     */
    public function getDia()
    {
        return $this->dia;
    }
}

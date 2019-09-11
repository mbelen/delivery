<?php

namespace Backend\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="dia")
 * @ORM\Entity()
 */
class Dia
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=100,nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="short", type="string", length=100,nullable=true)
     */
    private $short;
    
    /**
     * @ORM\Column(name="nro", type="integer")
     */
    private $nro;

    /**
    * @ORM\OneToMany(targetEntity="Horario", mappedBy="dia")
    */
   private $horarios;

    /**
     * @ORM\ManyToMany(targetEntity="\Backend\CustomerAdminBundle\Entity\Promocion", mappedBy="dias")
     */

    private $promociones;



    /**
     * Constructor
     */
    public function __construct()
    {
        
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
     * @return Dia
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
     * Add horarios
     *
     * @param \Backend\AdminBundle\Entity\Horario $horarios
     * @return Dia
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
     * Set short
     *
     * @param string $short
     * @return Dia
     */
    public function setShort($short)
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Get short
     *
     * @return string 
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Set nro
     *
     * @param integer $nro
     * @return Dia
     */
    public function setNro($nro)
    {
        $this->nro = $nro;

        return $this;
    }

    /**
     * Get nro
     *
     * @return integer 
     */
    public function getNro()
    {
        return $this->nro;
    }

    /**
     * Add promociones
     *
     * @param \Backend\CustomerAdminBundle\Entity\Promocion $promociones
     * @return Dia
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
}

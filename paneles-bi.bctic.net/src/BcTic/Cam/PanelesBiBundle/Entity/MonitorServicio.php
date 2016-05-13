<?php

namespace BcTic\Cam\PanelesBiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MonitorServicio
 *
 * @ORM\Table(name="MONITOR_SERVICIO", uniqueConstraints={@ORM\UniqueConstraint(name="unique_idx", columns={"area", "sistema", "mes", "anno"})})
 * @ORM\Entity
 */
class MonitorServicio
{
    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=45, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="sistema", type="string", length=45, nullable=false)
     */
    private $sistema;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes", type="integer", nullable=false)
     */
    private $mes;

    /**
     * @var integer
     *
     * @ORM\Column(name="anno", type="integer", nullable=false)
     */
    private $anno;

    /**
     * @var integer
     *
     * @ORM\Column(name="incidencias", type="integer", options={"default" = 0}, nullable=false)
     */
    private $incidencias;

    /**
     * @var float
     *
     * @ORM\Column(name="uptime", type="float", precision=10, scale=0, options={"default" = 0}, nullable=false)
     */
    private $uptime;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set area
     *
     * @param string $area
     * @return MonitorServicio
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set sistema
     *
     * @param string $sistema
     * @return MonitorServicio
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;

        return $this;
    }

    /**
     * Get sistema
     *
     * @return string 
     */
    public function getSistema()
    {
        return $this->sistema;
    }

    /**
     * Set mes
     *
     * @param boolean $mes
     * @return MonitorServicio
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return boolean 
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set anno
     *
     * @param integer $anno
     * @return MonitorServicio
     */
    public function setAnno($anno)
    {
        $this->anno = $anno;

        return $this;
    }

    /**
     * Get anno
     *
     * @return integer 
     */
    public function getAnno()
    {
        return $this->anno;
    }

    /**
     * Set incidencias
     *
     * @param integer $incidencias
     * @return MonitorServicio
     */
    public function setIncidencias($incidencias)
    {
        $this->incidencias = $incidencias;

        return $this;
    }

    /**
     * Get incidencias
     *
     * @return integer 
     */
    public function getIncidencias()
    {
        return $this->incidencias;
    }

    /**
     * Set uptime
     *
     * @param float $uptime
     * @return MonitorServicio
     */
    public function setUptime($uptime)
    {
        $this->uptime = $uptime;

        return $this;
    }

    /**
     * Get uptime
     *
     * @return float 
     */
    public function getUptime()
    {
        return $this->uptime;
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
}

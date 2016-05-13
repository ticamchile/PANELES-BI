<?php

namespace BcTic\Cam\PanelesBiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetaSolicitudServicio
 *
 * @ORM\Table(name="META_SOLICITUD_SERVICIO", uniqueConstraints={@ORM\UniqueConstraint(name="unique_idx", columns={"area", "tipo", "mes", "anno", "estado"})})
 * @ORM\Entity
 */
class MetaSolicitudServicio
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
     * @ORM\Column(name="tipo", type="string", length=45, nullable=false)
     */
    private $tipo;

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
     * @ORM\Column(name="cantidad", type="integer", options={"default" = 0}, nullable=false)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="tiempo_de_atencion_promedio", options={"default" = 0}, type="integer", nullable=false)
     */
    private $tiempoDeAtencionPromedio;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=45, nullable=false)
     */
    private $estado;

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
     * @return MetaSolicitudServicio
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
     * Set tipo
     *
     * @param string $tipo
     * @return MetaSolicitudServicio
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     * @return MetaSolicitudServicio
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set anno
     *
     * @param integer $anno
     * @return MetaSolicitudServicio
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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return MetaSolicitudServicio
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
     * Set tiempoDeAtencionPromedio
     *
     * @param integer $tiempoDeAtencionPromedio
     * @return MetaSolicitudServicio
     */
    public function setTiempoDeAtencionPromedio($tiempoDeAtencionPromedio)
    {
        $this->tiempoDeAtencionPromedio = $tiempoDeAtencionPromedio;

        return $this;
    }

    /**
     * Get tiempoDeAtencionPromedio
     *
     * @return integer 
     */
    public function getTiempoDeAtencionPromedio()
    {
        return $this->tiempoDeAtencionPromedio;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return MetaSolicitudServicio
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
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

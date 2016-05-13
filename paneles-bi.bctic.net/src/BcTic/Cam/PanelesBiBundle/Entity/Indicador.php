<?php

namespace BcTic\Cam\PanelesBiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indicador
 *
 * @ORM\Table(name="INDICADOR", uniqueConstraints={@ORM\UniqueConstraint(name="idx_INDICADOR_lookup", columns={"area", "granularidad", "dia", "mes", "anno", "indicador"})})
 * @ORM\Entity
 */
class Indicador
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
     * @ORM\Column(name="granularidad", type="string", length=1, nullable=false)
     */
    private $granularidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="dia", type="integer", nullable=false)
     */
    private $dia;

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
     * @var string
     *
     * @ORM\Column(name="valor", type="string", type="string", length=30, options={"default" = 0}, nullable=false)
     */
    private $valor;

    /**
     * @var string
     *
     * @ORM\Column(name="indicador", type="string", length=100, nullable=false)
     */
    private $indicador;

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
     * @return Indicador
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
     * Set granularidad
     *
     * @param string $granularidad
     * @return Indicador
     */
    public function setGranularidad($granularidad)
    {
        $this->granularidad = $granularidad;

        return $this;
    }

    /**
     * Get granularidad
     *
     * @return string 
     */
    public function getGranularidad()
    {
        return $this->granularidad;
    }

    /**
     * Set dia
     *
     * @param integer $dia
     * @return Indicador
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return integer 
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     * @return Indicador
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
     * @return Indicador
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
     * Set valor
     *
     * @param string $valor
     * @return Indicador
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set indicador
     *
     * @param string $indicador
     * @return Indicador
     */
    public function setIndicador($indicador)
    {
        $this->indicador = $indicador;

        return $this;
    }

    /**
     * Get indicador
     *
     * @return string 
     */
    public function getIndicador()
    {
        return $this->indicador;
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

    public function __toString(){
      return $this->indicador;
    }
}

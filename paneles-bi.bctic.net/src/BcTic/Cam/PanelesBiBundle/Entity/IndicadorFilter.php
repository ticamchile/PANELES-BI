<?php

namespace BcTic\Cam\PanelesBiBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class IndicadorFilter
{
    
    private $fechaDesde;
    
    private $fechaHasta;

    /**
    *
    * @Assert\NotBlank()
    */
    private $indicadores;

    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }


    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    public function setIndicadores($indicadores)
    {
        $this->indicadores = $indicadores;

        return $this;
    }

    public function getIndicadores()
    {
        return $this->indicadores;
    }

}

<?php

namespace BcTic\Cam\PanelesBiBundle\Entity;


class Periodo
{

    private $panel;

    private $mes;

    private $anno;

    public function __construct() {
        $this->mes = date('m');
        $this->anno = date('Y');
    }

    public function setPanel($panel)
    {
        $this->panel = $panel;
        return $this;
    }

    /**
     * Get mes
     *
     * @return integer 
     */
    public function getPanel()
    {
        return $this->panel;
    }



    /**
     * Set mes
     *
     * @param integer $mes
     * @return Periodo
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
     * @return Periodo
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
}

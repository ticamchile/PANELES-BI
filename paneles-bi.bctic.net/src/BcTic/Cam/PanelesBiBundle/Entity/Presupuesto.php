<?php

namespace BcTic\Cam\PanelesBiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Presupuesto
 *
 * @ORM\Table(name="PRESUPUESTO")
 * @ORM\Entity
 */
class Presupuesto
{
    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=45, nullable=false)
     */
    private $area;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mes", type="boolean", nullable=false)
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
     * @ORM\Column(name="tipo", type="string", length=45, nullable=false)
     */
    private $tipo;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", precision=10, scale=0, nullable=false)
     */
    private $valor;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


}

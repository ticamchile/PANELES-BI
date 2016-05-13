<?php

namespace BcTic\VictoriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseEntity
 *
 * @ORM\Table(name="BaseEntity")
 * @ORM\MappedSuperClass
 */
class BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=25, precision=0, scale=0, nullable=false, unique=false)
     */
    private $state;


}

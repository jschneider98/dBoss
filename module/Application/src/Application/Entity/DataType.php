<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity @ORM\HasLifecycleCallbacks */
class DataType extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     **/
    protected $data_type_id;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $name;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $aliases;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $description;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $driver;

    /** @ORM\Column(type="datetime", unique=true, nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", unique=true, nullable=false) */
    protected $modification_date;

    /** @ORM\Column(type="datetime", unique=true, nullable=true) */
    protected $deletion_date;
}
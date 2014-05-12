<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="role")
 **/
class Role extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     **/
    protected $role_id;

    /** @ORM\Column(type="integer", unique=true, nullable=false) */
    protected $role_level;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $role_name;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $display_name;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $modification_date;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $deletion_date;
}
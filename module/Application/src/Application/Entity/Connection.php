<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="connection")
 **/
class Connection extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     **/
    protected $connection_id;

    /** @ORM\Column(type="integer", nullable=false) */
    protected $user_id;

    /** @ORM\Column(type="string", unique=true, nullable=true) */
    protected $display_name;

    /** @ORM\Column(type="string", unique=true, nullable=true) */
    protected $database_name;

    /** @ORM\Column(type="string", nullable=true) */
    protected $user_name;

    /** @ORM\Column(type="string", nullable=true) */
    protected $password;

    /** @ORM\Column(type="string", nullable=true) */
    protected $host;

    /** @ORM\Column(type="string", nullable=true) */
    protected $driver;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $modification_date;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="connections")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     **/
    protected $user;
}
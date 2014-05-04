<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity @ORM\HasLifecycleCallbacks */
class User extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     **/
    protected $user_id;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $user_name;

    /** @ORM\Column(type="string", nullable=false) */
    protected $first_name;

    /** @ORM\Column(type="string", nullable=false) */
    protected $last_name;

    /** @ORM\Column(type="string", nullable=false) */
    protected $password;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $modification_date;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $deletion_date;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     **/
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="Query", mappedBy="user")
     **/
    protected $queries;

    /**
     * @ORM\OneToMany(targetEntity="Server", mappedBy="user")
     **/
    protected $servers;

    /**
     * @ORM\OneToMany(targetEntity="Database", mappedBy="user")
     **/
    protected $databases;
}
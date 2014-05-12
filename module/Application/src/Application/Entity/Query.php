<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="query")
 **/
class Query extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     **/
    protected $query_id;

    /** @ORM\Column(type="integer", nullable=false) */
    protected $user_id;

    /** @ORM\Column(type="string", unique=true, nullable=true) */
    protected $query_name;

    /** @ORM\Column(type="text", nullable=false) */
    protected $query;

    /** @ORM\Column(type="string", nullable=false) */
    protected $query_hash;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $modification_date;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $deletion_date;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="queries")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     **/
    protected $user;
}
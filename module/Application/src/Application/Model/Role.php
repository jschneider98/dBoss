<?php

namespace Application\Model;

class Role extends AbstractEntity
{
    public $role_id;
    public $role_level;
    public $role_name;
    public $display_name;
    public $creation_date;
    public $modification_date;
    public $deletion_date;
}
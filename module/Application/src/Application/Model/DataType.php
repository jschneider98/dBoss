<?php

namespace Application\Model;

class DataType extends AbstractEntity
{
    public $data_type_id;
    public $name;
    public $aliases;
    public $description;
    public $driver;
    public $creation_date;
    public $modification_date;
    public $deletion_date;
}
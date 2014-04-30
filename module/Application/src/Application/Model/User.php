<?php

namespace Application\Model;

class User extends AbstractEntity
{
    public user_id;
    public role_id;
    public user_name;
    public first_name;
    public last_name;
    public password;
    public creation_date;
    public modification_date;
    public deletion_date;
}
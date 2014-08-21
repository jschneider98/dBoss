<?php
/**
 * Schema Resource NULL: Null object
 */

namespace Dboss\Schema\Resource;

class Null extends ResourceAbstract
{

    public function getResourceList(array $params = array())
    {
        return array();
    }

    public function getEncodedResourceList(array $params = array())
    {
        return array();
    }

    public function getResourceDefinition(array $params = array())
    {
        return "";
    }

    public function getResourceListSql(array $params = array())
    {
        return "";
    }
}
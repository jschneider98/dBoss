<?php

/**
 * Factory that generates schema resource objects based on resource type and db driver name
 */

namespace Dboss\Schema\Resource;

use Dboss\Schema\Resource\PgSqlTable;

class ResourceFactory
{
    public static function getResource(array $params)
    {
        $resource_type = 'everything';
        $db = NULL;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $resource_type || ! is_string($resource_type)) {
            throw new \Exception("Invalid resource_type in " . __METHOD__);
        }

        if ( ! $db) {
            throw new \Exception("Database connection object is required in " . __METHOD__);
        }

        //$driver_name = $db->getDriver()->getName();

        // @TEMP
        return new PgSqlTable(array('db' => $db));

        if ($driver_name == "pdo_pgsql") {
            switch ($resource_type) {
                case "table":
                    return new PgSqlTable(array('db' => $db));
                    break;
                case "view":
                    return new PgSqlView(array('db' => $db));
                    break;
                case "schema":
                    return new PgSqlSchema(array('db' => $db));
                    break;
                case "function":
                    return new PgSqlFunction(array('db' => $db));
                    break;
                case "sequence":
                    return new PgSqlSequence(array('db' => $db));
                    break;
                case "type":
                    return new PgSqlType(array('db' => $db));
                    break;
                case "database":
                    return new PgSqlDatabase(array('db' => $db));
                    break;
                case "everything":
                    return new PgSqlEverything(array('db' => $db));
                    break;
            }
        }

        if ($driver_name == "pdo_sqlite") {
            if ($resource_type == "table" || $resource_type == "everything") {
                return new SQLBoss_Schema_Resource_SqliteTable(array('db' => $db));
            }
        }
        
        return new SQLBoss_Schema_Resource_Null(array('db' => $db));
    }

    /**
     *  It's very important to not include the "everything" resource
     * */
    public static function getAllResources(array $params)
    {
        $db = NULL;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $db) {
            throw new \Exception("Database connection object is required in " . __METHOD__);
        }

        return array(
            new SQLBoss_Schema_Resource_PgSqlTable(array('db' => $db)),
            new SQLBoss_Schema_Resource_PgSqlView(array('db' => $db)),
            new SQLBoss_Schema_Resource_PgSqlSchema(array('db' => $db)),
            new SQLBoss_Schema_Resource_PgSqlFunction(array('db' => $db)),
            new SQLBoss_Schema_Resource_PgSqlSequence(array('db' => $db)),
            new SQLBoss_Schema_Resource_PgSqlType(array('db' => $db))
        );
    }
}
dBoss
=======================

Introduction
------------
dBoss is a web based database administration and management tool.

Installation
------------

Using Composer (recommended)
----------------------------

@TODO

*Temporary Manual Config* (while setup script isn't done)
--------------------------------------------------------

- Clone dBoss
- Run ./composer.phar install (from dBoss root)

Edit config/autoload/local.php as follows:

    <?php

    return array(
        'security' => array(
            'salt_key' => 'your_salt_here',
            'iteration_count' => 8,
            'portable_hashes' => 0,
        ),
    );

NOTE: local.php is ignored by git, so it's safe to add connection info to it.

dBoss system db configuration:

Edit config/auotload/database.local.php as follows:

    <?php
    
    $root_dir = dirname(__DIR__) . '/..';
    
    return array(
      'doctrine' => array(
        'connection' => array(
          'orm_default' => array(
            'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
            'params' => array(
                'path' => $root_dir . "/data/db/system.db"
            )
          )
        )
      ),
    );
    
NOTE: database.local.php is ignored by git, so it's safe to add connection info to it.

Run from dBoss root directory:

*php public/index.php load-sqlite --withdata*

If your system db already exists and you want to unlink it and reload the data:

*php public/index.php load-sqlite --unlink --withdata*

Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName db-boss.localhost
        DocumentRoot /path/to/db-boss/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/db-boss/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

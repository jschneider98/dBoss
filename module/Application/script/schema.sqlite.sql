-- scripts/schema.sqlite.sql
--
-- You will need load your database schema with this SQL.

-- ** Role Table **

CREATE TABLE role (
    role_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    role_level INTEGER NOT NULL,
    role_name VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL,
    modification_date DATETIME NOT NULL,
    deletion_date DATETIME
);

-- ** Data Type Table **

CREATE TABLE data_type (
    data_type_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    aliases VARCHAR(255),
    description VARCHAR(255) NOT NULL,
    driver VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL,
    modification_date DATETIME NOT NULL,
    deletion_date DATETIME
);

-- ** User Table **

CREATE TABLE user (
    user_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL,
    modification_date DATETIME NOT NULL,
    deletion_date DATETIME,
    FOREIGN KEY (role_id) REFERENCES role (role_id)
);

CREATE INDEX "user_user_id" ON "user" ("user_id");

-- ** Query Table **

CREATE TABLE query (
    query_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    query_name VARCHAR(255),
    sql TEXT NOT NULL,
    sql_hash VARCHAR(255),
    creation_date DATETIME NOT NULL,
    modification_date DATETIME NOT NULL,
    deletion_date DATETIME,
    FOREIGN KEY (user_id) REFERENCES user (user_id)
);

CREATE INDEX "query_query_id" ON "query" ("query_id");
CREATE INDEX "query_user_id" ON "query" ("user_id");
CREATE INDEX "query_query_name" ON "query" ("query_name");
CREATE INDEX "query_modificatoin_date" ON "query" ("modification_date");

-- ** Server Table **

CREATE TABLE server (
    server_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name VARCHAR(255),
    user_name VARCHAR(255),
    password VARCHAR(255),
    host VARCHAR(255),
    driver VARCHAR(255),
    creation_date DATETIME NOT NULL,
    modification_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_id (user_id)
);

-- ** Database Table **

CREATE TABLE database (
    database_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    display_name VARCHAR(255),
    name VARCHAR(255),
    user_name VARCHAR(255),
    password VARCHAR(255),
    host VARCHAR(255),
    driver VARCHAR(255),
    creation_date DATETIME NOT NULL,
    modification_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_id (user_id)
);
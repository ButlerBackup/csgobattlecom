<?php

/**
 * MySQLi
 */
class Mysqli_DB
{
    static public $_db;

    /**
     * Database
     */
    final static public function Database()
    {
        if (!self::$_db) {
            self::$_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if (mysqli_connect_errno()) {
                exit('Error DB...');
            } else {
                self::$_db->query("SET NAMES 'utf8'");
                self::$_db->query("SET CHARACTER SET 'utf8'");
            }
        }
    }

}
/* End of file */
<?php

class MySQL {

    public $connection = null;
    public $user = null;
    public $password = null;
    public $name = null;
    public $encoding = 'UTF8';
    public $host = '127.0.0.1';

    public function __construct($user, $password, $name, $host = '127.0.0.1', $encoding = 'UTF8', $do_not_start_pdo = false) {
        if ($do_not_start_pdo) {
            return $this;
        }
        $dsn = 'mysql:dbname=' . $name . ';host=' . $host;
        $this->user = trim(strval($user));
        $this->passwort = trim(strval($password));
        $this->name = trim(strval($name));
        $this->connection = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    }

    public static function init_self($function) {
        $MySQL = new MySQL(null, null, null, null, null, true);
        if (is_callable($function)) {
            $function($MySQL);
        }
        return $MySQL;
    }

    public function query($sql) {
        $sql = trim(stripslashes($sql));
        return $this->connection->query($sql, PDO::FETCH_ASSOC);
    }

    public function query_select($sql) {
        $return = array();
        $query = $this->query($sql);
        if ($query) {
            foreach ($query as $row) {
                array_push($return, $row);
            }
        }
        return $return;
    }

    public function query_select_first($sql) {
        $query = $this->query($sql);
        if ($query) {
            foreach ($query as $row) {
                return $row;
            }
        }
        return null;
    }

    public function info_tables() {
        $tables = array();
        if ($this->connection) {
            foreach ($this->query_select('SHOW TABLES') as $row) {
                array_push($tables, reset($row));
            }
        }
        return $tables;
    }

}

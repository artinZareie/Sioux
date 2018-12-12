<?php

namespace App\Libraries;


use App\Config\Database as properties;
use \PDO;
use \PDOException;

final class DB
{
    protected $connection;
    protected $errors = [];
    protected $stmt = "";

    public static function __callStatic($name, $arguments)
    {
        $db = new static();
        return $db->{$name}(...$arguments);
    }

    public function __construct()
    {
        $this->Connect();
    }

    protected function Connect()
    {
        try {
            $this->connection = new \PDO(properties::PDO_DRIVER . ":" . "host=" . properties::CONNECTION_HOST . ";" . "dbname=" . properties::PDO_CONNECTION_DATABASE . ";charset=" . properties::PDO_CONNECTION_SET_COLLECTION, properties::CONNECTION_USERNAME, properties::CONNECTION_PASSWORD, properties::PDO_OPTIONS);
            return true;
        } catch (\PDOException $e) {
            array_push($this->errors, $e->getMessage());
            return $this->errors;
        }
    }

    protected function get_errors()
    {
        return $this->errors;
    }

    protected function sql_injection($str)
    {
        return preg_match("/[\`\'\"\/\\]+/", $str);
    }

    protected function prepare($sql)
    {
        $this->stmt = $this->connection->prepare($sql);
        return $this;
    }

    protected function bind($name, $value, $type = null)
    {
        if ($type == null) {
            switch ($value) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_string($value):
                    $type = PDO::PARAM_STR;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }
        $this->stmt->bindValue($name, $value, $type);
    }

    protected function execute()
    {
        return $this->stmt->execute();
    }

    protected function result()
    {
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    protected function num_rows()
    {
        return $this->stmt->rowCount();
    }

    protected function select(string $select, string $from, array $where = [], array $binds = [], $limit = null)
    {
        $query = "SELECT " . $select;
        $query .= " FROM `" . $from . "`";
        if ((isset($where)) && (!empty($where))) {
            $query .= " WHERE 1 ";
            foreach ($where as $item => $value) {
                $query .= " AND " . $item . " = " . $value;
            }
        }
        if (isset($limit)) {
            $query = " LIMIT " . $limit;
        }
        $this->prepare($query);
        foreach ($binds as $bind => $value) {
            $this->bind($bind, $value);
        }
        $this->execute();
        $data = $this->result();
        return $data;
    }

    public function update(string $table, array $values, array $where = [])
    {
        $query = "UPDATE `$table` SET ";
        $keys = array_keys($values);
        $content = array_values($values);
        $query .= " `$keys[0]` = $content[0] ";
        if (isset($keys[1]) && isset($content[1])) {
            $secendKey = array_slice($keys, 1);
            $secendContent = array_slice($content, 1);
            foreach ($secendKey as $id => $keyname) {
                $query .= " ,`$keyname` = $secendContent[$id]";
            }
        }
        if ((isset($where)) && (!empty($where))) {
            $query .= " WHERE 1 ";
            foreach ($where as $item => $value) {
                $query .= " AND " . $item . " = " . $value;
            }
        }
        $this->prepare($query);
        return $this->execute();
    }

    public function insert(string $table, array $values, array $binds = [])
    {
        $query = "INSERT INTO `$table` (";
        $keys = array_keys($values);
        $content = array_values($values);
        $query .= "`" . $keys[0] . "`";
        if (isset($keys[1])) {
            $secends = array_slice($keys, 1);
            foreach ($secends as $value) {
                $query .= " ,`" . $value . "`";
            }
        }
        $query .= ")";
        $query .= " VALUES ( ";
        $query .= $content[0];
        if (isset($keys[1])) {
            $contents = array_slice($content, 1);
            foreach ($contents as $value) {
                $query .= " ," . $value;
            }
        }
        $query .= ");";
        var_dump($query);
        $this->prepare($query);
        foreach ($binds as $bind => $value) {
            $this->bind($bind, $value);
        }
        return $this->execute();
    }
}
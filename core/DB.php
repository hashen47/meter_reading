<?php

class DB
{
    private string $host;
    private string $dbname;
    private string $dbuser;
    private string $dbpsw;
    private PDO $conn;
    private bool $debug = true;

    public function __construct()
    {
        $host = "localhost";
        $dbname = "meter_readings";
        $dbuser = "root";
        $dbpsw = "";

        // connection
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpsw);
            if ($this->debug) {
                // echo "pdo connection is alive";
            }
        } catch (PDOException $e) {
            if ($this->debug) {
                echo $e->getMessage();
                die();
            }
        }
    }

    public function find($query, $values = [])
    {
        try {
            $sql = $this->conn->prepare($query);
            $sql->execute($values);
            return $sql->fetch();
        } catch (PDOException $e) {
            if ($this->debug)
                echo $e->getMessage();
            die();
        }
    }

    public function findAll($query, $values = [])
    {
        try {
            $data = [];
            $sql = $this->conn->prepare($query);
            $sql->execute($values);
            $data = $sql->fetchAll();
            return $data;
        } catch (PDOException $e) {
            if ($this->debug)
                echo $e->getMessage();
            die();
        }
    }

    public function save($query, $values)
    {
        try {
            $this->conn->beginTransaction();
            $sql = $this->conn->prepare($query);
            $sql->execute($values);
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            if ($this->debug) {
                echo $e->getMessage();
            }
            die();
        }
    }

    public function rowCount($query, $values) {
        try {
            return count($this->findAll($query, $values));
        } catch (PDOException $e) {
            if ($this->debug)
                echo $e->getMessage();
            die();
        }
    }
}

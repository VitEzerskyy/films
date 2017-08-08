<?php

class DB {

    protected $connection;

    public function __construct($dsn, $user, $password){

        try {
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

    }

    public function execute($sql, array $arr = null, $is_insert = null) {
        if (!$this->connection) {
            return false;
        }

        try {
            $sth = $this->connection->prepare($sql);
            $sth->execute($arr);
        }catch (PDOException $e) {
            echo $e->getMessage();

    }
        if (!$is_insert) {
            $data = $sth->fetchAll();
        } else {
            $data = true;
        }


        return $data;
}

}
<?php

namespace helper;
class Database
{
    private $conn;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function uniqueQuery($sql, $dato)
    {
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row[$dato] : ""; // Retorna solo el valor de la columna `$dato`
        }
        return "";
    }

    public function queryPuntaje($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row['puntaje'] : ""; // Retorna solo el valor de la columna `puntaje`
        }
        return "";
    }

    public function queryRespuesta($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row ? $row['respuesta'] : ""; // Retorna solo el valor de la columna `respuesta`
        }
        return "";
    }

    public function execute($sql)
    {
        mysqli_query($this->conn, $sql);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }

}
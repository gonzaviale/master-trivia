<?php
namespace model;

class RespuestaModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function buscarRespuestas($id_pregunta)
    {
        return $this->database->query("SELECT * FROM respuesta WHERE pregunta_id='$id_pregunta'");

    }

    public function devolverSiEsCorrecta($id_respuesta)
    {
        return $this->database->query("SELECT * FROM respuesta WHERE id='$id_respuesta' AND correcta='1'");
    }

}
<?php

namespace model;

class PreguntaModel
{

    private $database;
    public function __construct($database)
    {
        $this->database=$database;
    }

    public function buscarPreguntas()
    {
        return $this->database->query("SELECT * FROM pregunta");

    }
    public function buscarPregunta($id)
    {
        return $this->database->query("SELECT * FROM pregunta WHERE id='$id'");

    }

}

<?php

class EliminarPreguntaEditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasEditor(){
        return $this->database->query("SELECT id, pregunta FROM pregunta");
    }

    public function eliminarPregunta($id){
        $this->database->execute("DELETE FROM respuesta WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM pregunta WHERE id = $id");
    }
}
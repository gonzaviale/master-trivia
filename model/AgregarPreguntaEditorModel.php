<?php

class AgregarPreguntaEditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function agregarPregunta($pregunta, $categoria_id){
        $date = $this->obtenerFecha();
        $this->database->execute("INSERT INTO pregunta(pregunta, respuestas_correctas, respuestas_incorrectas, total_respuestas, categoria_id, dificultad_id, fecha_creacion) VALUES 
                                                    ('$pregunta', '5', '5', '10', '$categoria_id', '2', '$date')");
    }

    public function agregarRespuestaCorrecta($respuesta, $pregunta){
        $id_pregunta = $this->buscarIdPorPregunta($pregunta);
        $this->database->execute("INSERT INTO respuesta(respuesta, pregunta_id, correcta) VALUES 
                                                    ('$respuesta', '$id_pregunta', '1')");
    }

    public function agregarRespuestaIncorrecta($respuesta, $pregunta){
        $id_pregunta = $this->buscarIdPorPregunta($pregunta);
        $this->database->execute("INSERT INTO respuesta(respuesta, pregunta_id, correcta) VALUES 
                                                    ('$respuesta', '$id_pregunta', '0')");
    }
    private function buscarIdPorPregunta($pregunta)
    {
        return $this->database->uniqueQuery("SELECT id FROM pregunta WHERE pregunta='$pregunta'", 'id');

    }

    private function obtenerFecha() {
        // Establecer la zona horaria (UTC-3)
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        return date('Y-m-d');
    }

}
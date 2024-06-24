<?php

class SugerirPreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function agregarPreguntaSugerida($categoria_id, $pregunta, $respuesta_correcta, $incorrecta_1, $incorrecta_2, $incorrecta_3){
        $this->database->execute("INSERT INTO pregunta_sugerida (categoria_id, pregunta, respuesta_correcta, incorrecta_1, incorrecta_2, incorrecta_3)
                                   VALUES ('$categoria_id', '$pregunta', '$respuesta_correcta', '$incorrecta_1', '$incorrecta_2', '$incorrecta_3')");
    }
}
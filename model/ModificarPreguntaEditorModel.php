<?php

class ModificarPreguntaEditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasEditor(){
        return $this->database->query("SELECT id, pregunta FROM pregunta");
    }

    public function obtenerDatosPreguntaPorId($id){
        return $this->database->query("SELECT p.id, p.pregunta AS pregunta,
       c.id AS id_categoria,
       c.categoria AS categoria,
       MAX(CASE WHEN r.correcta = 1 THEN r.respuesta END) AS respuesta_correcta,
       MAX(CASE WHEN r.correcta = 0 AND r.id = r_correcta.id + 1 THEN r.respuesta END) AS incorrecta_1,
       MAX(CASE WHEN r.correcta = 0 AND r.id = r_correcta.id + 2 THEN r.respuesta END) AS incorrecta_2,
       MAX(CASE WHEN r.correcta = 0 AND r.id = r_correcta.id + 3 THEN r.respuesta END) AS incorrecta_3 
        FROM pregunta p INNER JOIN respuesta r ON p.id = r.pregunta_id 
            INNER JOIN categoria c ON p.categoria_id = c.id 
            INNER JOIN respuesta r_correcta ON p.id = r_correcta.pregunta_id AND r_correcta.correcta = 1 
        WHERE p.id = $id");
    }

    public function modificarPregunta($idPregunta, $categoria_id, $pregunta, $respuesta_correcta, $incorrecta_1, $incorrecta_2, $incorrecta_3){
        $this->database->execute("UPDATE pregunta SET pregunta = '$pregunta', categoria_id = $categoria_id WHERE id = $idPregunta");

        $this->database->execute("UPDATE respuesta SET respuesta = '$respuesta_correcta' WHERE pregunta_id = $idPregunta AND correcta = 1");
        $this->database->execute("UPDATE respuesta SET respuesta = '$incorrecta_1' WHERE pregunta_id = $idPregunta AND correcta = 0 AND id = (SELECT id FROM respuesta WHERE pregunta_id = $idPregunta AND correcta = 1) + 1");
        $this->database->execute("UPDATE respuesta SET respuesta = '$incorrecta_2' WHERE pregunta_id = $idPregunta AND correcta = 0 AND id = (SELECT id FROM respuesta WHERE pregunta_id = $idPregunta AND correcta = 1) + 2");
        $this->database->execute("UPDATE respuesta SET respuesta = '$incorrecta_3' WHERE pregunta_id = $idPregunta AND correcta = 0 AND id = (SELECT id FROM respuesta WHERE pregunta_id = $idPregunta AND correcta = 1) + 3");

    }


}
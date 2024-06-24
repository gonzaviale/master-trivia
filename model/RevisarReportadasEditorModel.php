<?php

class RevisarReportadasEditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasReportadasEditor(){
        return $this->database->query("SELECT * FROM reportada");
    }

    public function obtenerDatosReportada($id){
        return $this->database->query("SELECT rep.id_pregunta AS id, 
       p.pregunta AS pregunta_reportada, 
       c.categoria AS categoria, 
       MAX(CASE WHEN r.correcta = 1 THEN r.respuesta END) AS respuesta_correcta,
       MAX(CASE WHEN r.correcta = 0 AND r.id = r_correcta.id + 1 THEN r.respuesta END) AS incorrecta_1,
       MAX(CASE WHEN r.correcta = 0 AND r.id = r_correcta.id + 2 THEN r.respuesta END) AS incorrecta_2,
       MAX(CASE WHEN r.correcta = 0 AND r.id = r_correcta.id + 3 THEN r.respuesta END) AS incorrecta_3,
       rep.id_jugador AS id_jugador_que_reporto FROM pregunta p INNER JOIN respuesta r ON p.id = r.pregunta_id 
           INNER JOIN categoria c ON p.categoria_id = c.id 
           INNER JOIN respuesta r_correcta ON p.id = r_correcta.pregunta_id AND r_correcta.correcta = 1 
           INNER JOIN reportada rep ON p.id = rep.id_pregunta WHERE rep.id_pregunta = $id");
    }

    public function aprobarReportada($id){
        $this->database->execute("DELETE FROM respuesta WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM reportada WHERE id_pregunta = $id");
        $this->database->execute("DELETE FROM pregunta_respondida WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM pregunta WHERE id = $id");
    }

    public function eliminarReportada($id){
        $this->database->execute("DELETE FROM reportada WHERE id_pregunta = $id");
    }
}
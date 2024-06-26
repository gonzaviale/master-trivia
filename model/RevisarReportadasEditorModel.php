<?php

class RevisarReportadasEditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasReportadasEditor(){
        return $this->database->query("SELECT p.id AS id,
       p.pregunta AS pregunta_reportada,
       c.categoria AS categoria,
       GROUP_CONCAT(CASE WHEN r.correcta = 1 THEN r.respuesta ELSE NULL END ORDER BY r.id ASC) AS respuesta_correcta,
       GROUP_CONCAT(CASE WHEN r.correcta = 0 THEN r.respuesta ELSE NULL END ORDER BY r.id ASC) AS respuestas_incorrectas
FROM reportada rep
JOIN pregunta p ON rep.id_pregunta = p.id
JOIN respuesta r ON p.id = r.pregunta_id
JOIN categoria c ON p.categoria_id = c.id
GROUP BY rep.id, p.id
ORDER BY rep.id, p.id");
    }

    public function aprobarReporte($id){
        $this->database->execute("DELETE FROM respuesta WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM reportada WHERE id_pregunta = $id");
        $this->database->execute("DELETE FROM pregunta_respondida WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM pregunta WHERE id = $id");
    }

    public function eliminarReporte($id){
        $this->database->execute("DELETE FROM reportada WHERE id_pregunta = $id");
    }
}
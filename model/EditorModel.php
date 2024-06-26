<?php

class EditorModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasEditor(){
        return $this->database->query("SELECT p.id,
       p.pregunta,
       c.categoria,
       GROUP_CONCAT(CASE WHEN r.correcta = 1 THEN r.respuesta ELSE NULL END ORDER BY r.id ASC) AS respuesta_correcta,
       GROUP_CONCAT(CASE WHEN r.correcta = 0 THEN r.respuesta ELSE NULL END ORDER BY r.id ASC) AS respuestas_incorrectas 
        FROM pregunta p
        JOIN respuesta r ON p.id = r.pregunta_id
        JOIN categoria c ON p.categoria_id = c.id
        GROUP BY p.id
ORDER BY p.id");
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

    public function eliminarPregunta($id){
        $this->database->execute("DELETE FROM reportada WHERE id_pregunta = $id");
        $this->database->execute("DELETE FROM pregunta_respondida WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM respuesta WHERE pregunta_id = $id");
        $this->database->execute("DELETE FROM pregunta WHERE id = $id");
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

    public function obtenerPreguntasSugeridasEditor(){
        return $this->database->query("SELECT * FROM pregunta_sugerida");
    }

    public function aceptarSugerida($id){
        //Agrego Pregunta
        $pregunta = $this->buscarPreguntaPorIdSugerida($id);
        $categoria_id = $this->buscarIdCategoriaPorIdSugerida($id);
        $date = $this->obtenerFecha();
        $this->database->execute("INSERT INTO pregunta(pregunta, respuestas_correctas, respuestas_incorrectas, total_respuestas, categoria_id, dificultad_id, fecha_creacion) VALUES 
                                                    ('$pregunta', '5', '5', '10', '$categoria_id', '2', '$date')");

        //Agrego rta correcta
        $respuesta_correcta = $this->buscarCorrectaPorIdSugerida($id);
        $id_pregunta = $this->buscarIdPorPregunta($pregunta);
        $this->database->execute("INSERT INTO respuesta(respuesta, pregunta_id, correcta) VALUES 
                                                    ('$respuesta_correcta', '$id_pregunta', '1')");

        //Agrego las tres rtas incorrectas
        $incorrecta_1 = $this->buscarIncorrecta1PorIdSugerida($id);
        $this->database->execute("INSERT INTO respuesta(respuesta, pregunta_id, correcta) VALUES 
                                                    ('$incorrecta_1', '$id_pregunta', '0')");

        $incorrecta_2 = $this->buscarIncorrecta2PorIdSugerida($id);
        $this->database->execute("INSERT INTO respuesta(respuesta, pregunta_id, correcta) VALUES 
                                                    ('$incorrecta_2', '$id_pregunta', '0')");

        $incorrecta_3 = $this->buscarIncorrecta3PorIdSugerida($id);
        $this->database->execute("INSERT INTO respuesta(respuesta, pregunta_id, correcta) VALUES 
                                                    ('$incorrecta_3', '$id_pregunta', '0')");

        //Elimino de la tabla pregunta sugerida
        $this->database->execute("DELETE FROM pregunta_sugerida WHERE id = $id");

    }

    public function rechazarSugerida($id){
        $this->database->execute("DELETE FROM pregunta_sugerida WHERE id = $id");
    }

    public function obtenerReportada($idPregunta)
    {
        return $this->database->uniqueQuery("SELECT id FROM reportada WHERE id_pregunta = $idPregunta", "id");
    }

    public function eliminarReporte($id){
        $this->database->execute("DELETE FROM reportada WHERE id = '$id'");
    }

    private function buscarPreguntaPorIdSugerida($id){
        return $this->database->uniqueQuery("SELECT pregunta FROM pregunta_sugerida WHERE id = $id", 'pregunta');
    }

    private function buscarIdCategoriaPorIdSugerida($id){
        return $this->database->uniqueQuery("SELECT categoria_id FROM pregunta_sugerida WHERE id = $id", 'categoria_id');
    }

    private function buscarCorrectaPorIdSugerida($id)
    {
        return $this->database->uniqueQuery("SELECT respuesta_correcta FROM pregunta_sugerida WHERE id = $id", 'respuesta_correcta');

    }

    private function buscarIdPorPregunta($pregunta)
    {
        return $this->database->uniqueQuery("SELECT id FROM pregunta WHERE pregunta='$pregunta'", 'id');
    }

    private function buscarIncorrecta1PorIdSugerida($id)
    {
        return $this->database->uniqueQuery("SELECT incorrecta_1 FROM pregunta_sugerida WHERE id = $id", 'incorrecta_1');

    }

    private function buscarIncorrecta2PorIdSugerida($id)
    {
        return $this->database->uniqueQuery("SELECT incorrecta_2 FROM pregunta_sugerida WHERE id = $id", 'incorrecta_2');

    }

    private function buscarIncorrecta3PorIdSugerida($id)
    {
        return $this->database->uniqueQuery("SELECT incorrecta_3 FROM pregunta_sugerida WHERE id = $id", 'incorrecta_3');
    }

    private function obtenerFecha() {
        // Establecer la zona horaria (UTC-3)
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        return date('Y-m-d');
    }

}
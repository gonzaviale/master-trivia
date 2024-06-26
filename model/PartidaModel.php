<?php

namespace model;

use Exception;

class PartidaModel
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

    public function buscarRespuestas($id_pregunta)
    {
        return $this->database->query("SELECT * FROM respuesta WHERE pregunta_id='$id_pregunta'");

    }

    public function devolverSiEsCorrecta($id_respuesta)
    {
        return $this->database->query("SELECT * FROM respuesta WHERE id='$id_respuesta' AND correcta='1'");
    }

    public function agregarPreguntaRespondida($idPregunta, $username, $fechaHora)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        $this->database->execute("INSERT INTO pregunta_respondida (pregunta_id, jugador_id, fecha_hora) VALUES ('$idPregunta', '$idJugador', '$fechaHora')");
    }

    public function buscarPreguntaRespondida($idPregunta, $username)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        return $this->database->query("SELECT * FROM pregunta_respondida WHERE pregunta_id='$idPregunta' AND jugador_id='$idJugador'");
    }

    public function reiniciarPreguntasRepondidas($username)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        $this->database->execute("DELETE FROM pregunta_respondida WHERE jugador_id='$idJugador'");
    }

    public function buscarPreguntaPorId($id)
    {
        return $this->database->query("SELECT * FROM pregunta WHERE id='$id'");

    }

    public function guardarPartida($idPregunta, $username, $fechaHora, $puntos)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        $this->database->execute("INSERT INTO partida(jugador_id, puntaje, id_ultima_pregunta, fecha_hora) VALUES 
                                                    ('$idJugador', '$puntos', '$idPregunta', '$fechaHora')");
    }

    public function agregarCorrectaAJugador($username){
        $idJugador = $this->buscarJugadorPorUsername($username);
        $this->database->execute("UPDATE jugador SET respuestas_correctas = respuestas_correctas + 1, total_respuestas = total_respuestas + 1 WHERE id = '$idJugador'");
        $incorrectas = $this->database->uniqueQuery("SELECT respuestas_incorrectas FROM jugador WHERE id = '$idJugador'",'respuestas_incorrectas');
        $total = $this->database->uniqueQuery("SELECT total_respuestas FROM jugador WHERE id = '$idJugador'", 'total_respuestas');
        if($incorrectas/$total >= 0.70)
        {
            $this->database->execute("UPDATE jugador SET nivel_id = 1 WHERE id = '$idJugador'");
        } elseif($incorrectas/$total <= 0.30)
        {
            $this->database->execute("UPDATE jugador SET nivel_id = 3 WHERE id = '$idJugador'");
        } else
        {
            $this->database->execute("UPDATE jugador SET nivel_id = 2 WHERE id = '$idJugador'");
        }
    }

    public function agregarCorrectaAPregunta($idPregunta){
        $this->database->execute("UPDATE pregunta SET respuestas_correctas = respuestas_correctas + 1, total_respuestas = total_respuestas + 1 WHERE id = '$idPregunta'");
        $incorrectas = $this->database->uniqueQuery("SELECT respuestas_incorrectas FROM pregunta WHERE id = '$idPregunta'",'respuestas_incorrectas');
        $total = $this->database->uniqueQuery("SELECT total_respuestas FROM pregunta WHERE id = '$idPregunta'", 'total_respuestas');
        if($incorrectas/$total >= 0.70)
        {
            $this->database->execute("UPDATE pregunta SET dificultad_id = 3 WHERE id = '$idPregunta'");
        } elseif($incorrectas/$total <= 0.30)
        {
            $this->database->execute("UPDATE pregunta SET dificultad_id = 1 WHERE id = '$idPregunta'");
        } else
        {
            $this->database->execute("UPDATE pregunta SET dificultad_id = 2 WHERE id = '$idPregunta'");
        }
    }

    public function agregarIncorrectaAJugador($username){
        $idJugador = $this->buscarJugadorPorUsername($username);
        $this->database->execute("UPDATE jugador SET respuestas_incorrectas = respuestas_incorrectas + 1, total_respuestas = total_respuestas + 1 WHERE id = '$idJugador'");
        $incorrectas = $this->database->uniqueQuery("SELECT respuestas_incorrectas FROM jugador WHERE id = '$idJugador'",'respuestas_incorrectas');
        $total = $this->database->uniqueQuery("SELECT total_respuestas FROM jugador WHERE id = '$idJugador'", 'total_respuestas');
        if($incorrectas/$total >= 0.70)
        {
            $this->database->execute("UPDATE jugador SET nivel_id = 1 WHERE id = '$idJugador'");
        } elseif($incorrectas/$total <= 0.30)
        {
            $this->database->execute("UPDATE jugador SET nivel_id = 3 WHERE id = '$idJugador'");
        } else
        {
            $this->database->execute("UPDATE jugador SET nivel_id = 2 WHERE id = '$idJugador'");
        }
    }

    public function agregarIncorrectaAPregunta($idPregunta)
    {
        $this->database->execute("UPDATE pregunta SET respuestas_incorrectas = respuestas_incorrectas + 1, total_respuestas = total_respuestas + 1 WHERE id = '$idPregunta'");
        $incorrectas = $this->database->uniqueQuery("SELECT respuestas_incorrectas FROM pregunta WHERE id = '$idPregunta'",'respuestas_incorrectas');
        $total = $this->database->uniqueQuery("SELECT total_respuestas FROM pregunta WHERE id = '$idPregunta'", 'total_respuestas');
        if($incorrectas/$total >= 0.70)
        {
            $this->database->execute("UPDATE pregunta SET dificultad_id = 3 WHERE id = '$idPregunta'");
        } elseif($incorrectas/$total <= 0.30)
        {
            $this->database->execute("UPDATE pregunta SET dificultad_id = 1 WHERE id = '$idPregunta'");
        } else
        {
            $this->database->execute("UPDATE pregunta SET dificultad_id = 2 WHERE id = '$idPregunta'");
        }
    }

    public function finalizarPartida($idPregunta, $username)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        $puntaje = $this->buscarPuntaje($idJugador);
        $respuestaCorrecta = $this->buscarRespuestaCorrecta($idPregunta);
        return[
            'respuestaCorrectaFinal' => $respuestaCorrecta,
            'puntajeFinal' => $puntaje,
        ];
    }

    public function obtenerPreguntasDelNivel($nivelJugador)
    {
        return $this->database->query("SELECT * FROM pregunta WHERE dificultad_id = '$nivelJugador'");
    }

    public function obtenerNivelJugador($username)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        return $this->database->uniqueQuery("SELECT nivel_id FROM jugador WHERE id = '$idJugador'", 'nivel_id');
    }

    public function agregarPreguntaReportada($username, $idPregunta)
    {
        $idJugador = $this->buscarJugadorPorUsername($username);
        $yaLaReporto = $this->database->query("SELECT * FROM reportada WHERE id_jugador = '$idJugador' AND id_pregunta = '$idPregunta'");
        if(!$yaLaReporto)
        {
            $this->database->execute("INSERT INTO reportada(id_pregunta, id_jugador) VALUES ('$idPregunta', '$idJugador')");
        }
    }

    private function fueRespondida($pregunta)
    {
        $idPregunta = $pregunta['id'];
        $username = $_SESSION['username'];
        return $this->buscarPreguntaRespondida($idPregunta, $username);
    }

    public function buscarPreguntaSinResponder()
    {
        $nivelJugador = $this->obtenerNivelJugador($_SESSION['username']);
        $preguntasDelNivel = $this->obtenerPreguntasDelNivel($nivelJugador);
        // Obtener la longitud del array $preguntas
        $preguntasLength = count($preguntasDelNivel);

        do {
            $indiceAleatorio = array_rand($preguntasDelNivel);
            // Decrementar la longitud solo si la pregunta fue respondida
            if ($this->fueRespondida($preguntasDelNivel[$indiceAleatorio]) ) {
                $preguntasLength--;
            } else {
                break; // Encontró una pregunta no respondida, salir del loop
            }
        } while ($preguntasLength > 0);

        if ($preguntasLength == 0)
        {
            $username = $_SESSION["username"];
            $this->reiniciarPreguntasRepondidas($username);
        }

        $preguntaSeleccionada = $preguntasDelNivel[$indiceAleatorio];
        $idPregunta = $preguntaSeleccionada['id'];
        $username = $_SESSION['username'];
        $fechaHora = $this->obtenerFechaHora();
        $this->agregarPreguntaRespondida($idPregunta, $username, $fechaHora);

        return $preguntaSeleccionada;
    }

    private function obtenerFechaHora() {
        // Establecer la zona horaria (UTC-3)
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        return date('Y-m-d H:i:s');
    }

    public function buscarColorPorCategoria($categoriaId)
    {
        switch ($categoriaId){
            case 1: return ".container-partida{ background-color: #007bff5e }";
            case 2: return ".container-partida{ background-color: #ffd7005c }";
            case 3: return ".container-partida{ background-color: #4caf5099 }";
            case 4: return ".container-partida{ background-color: #f443365e }";
            default : return "";
        }
    }

    private function buscarRespuestaCorrecta($idPregunta)
    {
        return $this->database->queryRespuesta("SELECT * FROM respuesta WHERE pregunta_id='$idPregunta' AND correcta=1");
    }

    private function buscarJugadorPorUsername($username)
    {
        $idUsuario = $this->database->uniqueQuery("SELECT id FROM usuario WHERE nombre_usuario='$username'", 'id');
        return $this->database->uniqueQuery("SELECT id FROM jugador WHERE usuario_id='$idUsuario'", 'id');
    }

    private function buscarPuntaje($jugadorId)
    {
        return $this->database->queryPuntaje("SELECT * FROM partida WHERE jugador_id = $jugadorId ORDER BY fecha_hora DESC LIMIT 1;");
    }

}

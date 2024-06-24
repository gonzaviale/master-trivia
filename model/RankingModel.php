<?php

namespace model;
class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function mejores10Jugadores()
    {
        return $this->database->query("SELECT u.nombre_usuario, p.puntaje, u.foto_perfil  FROM jugador j JOIN
            partida p ON p.jugador_id = j.id JOIN usuario u ON j.usuario_id=u.id WHERE p.puntaje = (
                SELECT MAX(p2.puntaje) FROM partida p2 WHERE p2.jugador_id = j.id ) 
                                                                                 ORDER BY puntaje DESC LIMIT 10");

    }

    public function cantidad()
    {
        return $this->database->query("SELECT u.nombre_usuario , SUM(p.puntaje) as puntaje_total,u.foto_perfil  FROM jugador j JOIN
    partida p ON p.jugador_id = j.id JOIN usuario u ON j.usuario_id=u.id GROUP BY u.nombre_usuario ORDER BY puntaje_total DESC LIMIT 10");

    }
}
<?php

namespace model;
class UsuarioModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function mostrarPerfil($nombreUsuario)
    {
        $hayPartidas = $this->verificarJugadorPartidas($nombreUsuario);
        if (!$hayPartidas)
        {
            return $this->database->query("
                SELECT
                    u.nombre_usuario,
                    u.foto_perfil,
                    u.ciudad,
                    u.pais
                FROM
                    usuario u
                    JOIN jugador j ON j.usuario_id = u.id
                WHERE
                    u.nombre_usuario = '$nombreUsuario'
                ");
        } else
        {
            return $this->database->query("
        SELECT
            u.nombre_usuario,
            u.foto_perfil,
            u.ciudad,
            u.pais,
            SUM(p.puntaje) as puntaje_acumulado,
            COUNT(p.id) as partidasJugadas
        FROM
            usuario u
            JOIN jugador j ON j.usuario_id = u.id
            JOIN partida p ON p.jugador_id = j.id
        WHERE
            u.nombre_usuario = '$nombreUsuario'
        GROUP BY
            u.id
    ");
        }
    }

    private function verificarJugadorPartidas($nombreUsuario)
    {
        return $this->database->uniqueQuery("SELECT COUNT(p.id) as partidasJugadas FROM
            usuario u
            JOIN jugador j ON j.usuario_id = u.id
            JOIN partida p ON p.jugador_id = j.id
        WHERE
            u.nombre_usuario = '$nombreUsuario'
        GROUP BY u.id", "partidasJugadas");
    }
}
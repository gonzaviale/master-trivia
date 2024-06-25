<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function iniciarSesion($contrasenia, $nombreUsuario)
    {
        $passwordHash = md5($contrasenia);
        return $this->database->query("SELECT * FROM login JOIN usuario ON login.id_usuario=usuario.id WHERE login.password='$passwordHash' AND
                                                                      login.username='$nombreUsuario' AND usuario.cuenta_validada='1' ");

    }

    public function obtenerRol( $nombreUsuario)
    {
        $sql = "SELECT r.rol FROM login l JOIN usuario u ON l.id_usuario=u.id JOIN rol r ON r.id=l.id_rol WHERE u.nombre_usuario='$nombreUsuario'";
        return $this->database->uniqueQuery($sql, "rol");
    }

    public function obtenerPuntosJugador( $nombreUsuario)
    {
        return $this->database->uniqueQuery("SELECT MAX(p.puntaje) as puntaje FROM usuario u JOIN jugador j ON u.id=j.usuario_id
                      JOIN partida p ON j.id = p.jugador_id WHERE u.nombre_usuario='$nombreUsuario'", "puntaje");
    }

}

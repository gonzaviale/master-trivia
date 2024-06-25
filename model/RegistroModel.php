<?php

namespace model;

use Exception;

class RegistroModel
{

    private $database;

    public function __construct($database)
    {
        $this->database=$database;
    }

    public function buscarUsuario($email, $nombreUsuario)
    {
        return $this->database->query("SELECT * FROM usuario WHERE email='$email' OR nombre_usuario='$nombreUsuario' ");

    }

    public function cuentaValidada($email)
    {
        return $this->database->query("SELECT * FROM usuario WHERE email='$email' AND cuenta_validada='1' ");

    }

    public function buscarUsuarioPorToken($token)
    {
        return  $this->database->query("SELECT * FROM usuario WHERE  token_validacion = '$token'");


    }

    public function guardarTokenValidacion($email, $token)
    {
        return $this->database->execute("UPDATE usuario SET token_validacion = '$token' WHERE email = '$email' ");

    }

    public function validarCuenta($token)
    {
        return $this->database->execute("UPDATE usuario SET cuenta_validada ='1' WHERE token_validacion = '$token' ");

    }

    public function agregarUsuario($email,$nombreCompleto,$anioNacimiento,$sexo,$pais,$contrasenia,$nombreUsuario,$foto,$ciudad)
    {
        $this->database->execute("INSERT INTO usuario (email, nombre_completo, ano_nacimiento, sexo, pais, nombre_usuario, foto_perfil, ciudad) VALUES
                ('$email', '$nombreCompleto', '$anioNacimiento', '$sexo', '$pais', '$nombreUsuario', '$foto', '$ciudad')");
        $idUsuario = $this->database->uniqueQuery("SELECT id FROM usuario WHERE email='$email'", 'id');
        $this->agregarLogin($nombreUsuario,$contrasenia,$idUsuario);
        $this->agregarJugador($idUsuario);
    }

    private function agregarLogin($username,$password,$idUsuario)
    {
        $passwordHash = md5($password);
        if ($idUsuario) {
            $this->database->execute(
                "INSERT INTO login (username, password, id_usuario, id_rol,fecha_creacion) VALUES ('$username', '$passwordHash', '$idUsuario', '3',NOW())"
            );
        } else {

            throw new Exception('El usuario no existe en la tabla usuario.');
        }

    }

    private function agregarJugador($idUsuario)
    {
        // Verifica que $idUsuario no sea null antes de usarlo
        if ($idUsuario) {
            $this->database->execute("INSERT INTO jugador (respuestas_correctas, respuestas_incorrectas, total_respuestas, nivel_id, usuario_id) VALUES
                (5, 5 , 10, 2, '$idUsuario')");
        } else {
            // Manejo del error si $idUsuario es null
            throw new Exception('El usuario no existe en la tabla usuario.');
        }
    }
}
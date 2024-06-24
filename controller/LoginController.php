<?php

namespace controller;
class LoginController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {

        session_start();

        if (!isset($_SESSION['username'])) {

            $this->presenter->render("view/loginView.mustache");
            exit();
        }

        $username = $_SESSION['username'];
        $rol = $this->model->obtenerRol($username);
        $_SESSION['rol'] = $rol;
        if ($rol === "Administrador") {
            $this->presenter->render("view/adminView.mustache", ['nombre' => $username]);
            exit();
        } else if ($rol === "Editor") {
            if (isset($_SESSION['mensaje']))
            {
                $this->presenter->render("view/indexEditorView.mustache", ['mensaje' => $_SESSION['mensaje'], 'nombre' => $username]);
                unset($_SESSION['mensaje']);
                exit();
            }
            else
            {
                $this->presenter->render("view/indexEditorView.mustache", ['nombre' => $username]);
                exit();
            }
        }
        else {
            $puntaje = $this->model->obtenerPuntosJugador($_SESSION['username']);
            if (isset($_SESSION['mensaje']))
            {
                $this->presenter->render("view/indexView.mustache", ['mensaje' => $_SESSION['mensaje'],
                    'usuario' => $_SESSION['username'], 'puntaje' => $puntaje]);
                unset($_SESSION['mensaje']);
                exit();
            }
            $this->presenter->render("view/indexView.mustache", ['usuario' => $_SESSION['username'], 'puntaje' => $puntaje]);
            exit();
        }
    }

    public function procesarLogin()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = "Todos los campos son obligatorios.";
            return $this->presenter->render("view/loginView.mustache", ['error' => $error]);
        }

        if ($this->model->iniciarSesion($password, $username)) {
            // Inicio de sesión exitoso
            session_start();
            $_SESSION['username'] = $username;
            header("Location: /");
            exit();
        } else {
            // Credenciales inválidas
            $error = "Credenciales inválidas";
            return $this->presenter->render("view/loginView.mustache", ['error' => $error]);
        }
    }


}

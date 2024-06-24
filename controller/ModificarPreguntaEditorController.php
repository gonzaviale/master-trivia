<?php

namespace controller;

class ModificarPreguntaEditorController
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
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $preguntas = $this->model->obtenerPreguntasEditor();
        $this->presenter->render("view/listaModificarPreguntaEditorView.mustache", ['preguntas' => $preguntas]);
    }

    public function modificar(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $pregunta = $this->model->obtenerDatosPreguntaPorId($_GET['id']);
        $this->presenter->render("view/modificarPreguntaEditorView.mustache", ['pregunta' => $pregunta]);
    }

    public function procesarModificar(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $idPregunta = $_GET['id'];
        $categoria_id = $_POST['categoria'];
        $pregunta = $_POST['pregunta'];
        $respuesta_correcta = $_POST['respuesta_correcta'];
        $incorrecta_1 = $_POST['incorrecta_1'];
        $incorrecta_2 = $_POST['incorrecta_2'];
        $incorrecta_3 = $_POST['incorrecta_3'];

        if (empty($idPregunta) || empty($categoria_id) || empty($pregunta) || empty($respuesta_correcta) || empty($incorrecta_1) || empty($incorrecta_2) || empty($incorrecta_3)) {
            // Mostrar un mensaje de error
            $error = "Todos los campos son obligatorios.";
            $this->presenter->render("view/modificarPreguntaEditorView.mustache", ['error' => $error]);
        }

        $this->model->modificarPregunta($idPregunta, $categoria_id, $pregunta, $respuesta_correcta, $incorrecta_1, $incorrecta_2, $incorrecta_3);

        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "Pregunta modificada con exito";
        header("location: /");

    }

}
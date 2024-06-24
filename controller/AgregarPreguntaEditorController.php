<?php

namespace controller;

class AgregarPreguntaEditorController
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
        if($_SESSION['rol'] == "Editor"){
            $this->presenter->render("view/agregarPreguntaEditorView.mustache");
        } else {
            header("location: /");
        }

    }

    public function agregarPregunta(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $categoria_id = $_POST['categoria'];
        $pregunta = $_POST['pregunta_agregar'];
        $respuesta_correcta = $_POST['respuesta_correcta'];
        $incorrecta_1 = $_POST['incorrecta_1'];
        $incorrecta_2 = $_POST['incorrecta_2'];
        $incorrecta_3 = $_POST['incorrecta_3'];

        if (empty($categoria_id) || empty($pregunta) || empty($respuesta_correcta) || empty($incorrecta_1) || empty($incorrecta_2) || empty($incorrecta_3)) {
            // Mostrar un mensaje de error
            $error = "Todos los campos son obligatorios.";
            $this->presenter->render("view/agregarPreguntaEditorView.mustache", ['error' => $error]);
        }

        $this->model->agregarPregunta($pregunta, $categoria_id);
        $this->model->agregarRespuestaCorrecta($respuesta_correcta, $pregunta);
        $this->model->agregarRespuestaIncorrecta($incorrecta_1, $pregunta);
        $this->model->agregarRespuestaIncorrecta($incorrecta_2, $pregunta);
        $this->model->agregarRespuestaIncorrecta($incorrecta_3, $pregunta);

        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "Pregunta agregada con exito";
        header("location: /");

    }
}
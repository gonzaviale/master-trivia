<?php

namespace controller;

class EditorController
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
        foreach ($preguntas as $index => $pregunta)
        {
            $reportada = $this->model->obtenerReportada($pregunta['id']);
            $preguntas[$index]['reportada'] = $reportada;
        }
        $this->presenter->render("view/preguntasEditorView.mustache", ['preguntas' => $preguntas]);
    }

    public function eliminarReportada(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $this->model->eliminarReporte($_GET['id']);
        header("location: /editor/get");
    }

    public function modificarPregunta(){
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

    public function eliminarPregunta(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $this->model->eliminarPregunta($_GET['id']);
        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "Pregunta eliminada con exito";
        header("location: /");

    }

    public function accionAgregar(){
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

    public function revisarSugeridas(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $sugeridas = $this->model->obtenerPreguntasSugeridasEditor();
        $this->presenter->render("view/revisarSugeridasEditorView.mustache", ['sugeridas' => $sugeridas]);
    }

    public function aceptarSugerida(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $this->model->aceptarSugerida($_GET['id']);
        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "Pregunta agregada con exito";
        header("location: /");
    }

    public function rechazarSugerida(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $this->model->rechazarSugerida($_GET['id']);
        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "Pregunta rechazada con exito";
        header("location: /");
    }


}
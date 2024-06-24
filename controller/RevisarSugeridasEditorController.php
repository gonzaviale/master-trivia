<?php

namespace controller;

class RevisarSugeridasEditorController
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
        $sugeridas = $this->model->obtenerPreguntasSugeridasEditor();
        $this->presenter->render("view/revisarSugeridasEditorView.mustache", ['sugeridas' => $sugeridas]);
    }

    public function aceptar(){
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

    public function rechazar(){
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
<?php

namespace controller;

class EliminarPreguntaEditorController
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
        $this->presenter->render("view/eliminarPreguntaEditorView.mustache", ['preguntas' => $preguntas]);
    }

    public function eliminar(){
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
}
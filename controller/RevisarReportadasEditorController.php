<?php

namespace controller;

class RevisarReportadasEditorController
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
        $reportadas = $this->model->obtenerPreguntasReportadasEditor();
        $this->presenter->render("view/revisarReportadasEditorView.mustache", ['reportadas' => $reportadas]);
    }

    public function verReportada(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $reportada = $this->model->obtenerDatosReportada($_GET['id']);
        $this->presenter->render("view/accionesReportadaEditorView.mustache", ['reportada' => $reportada]);
    }

    public function aprobarReportada(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $this->model->aprobarReportada($_GET['id']);
        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "La pregunta reportada se elimino con exito";
        header("location: /");
    }

    public function eliminarReportada(){
        session_start();
        if($_SESSION['rol'] != "Editor"){
            header("location: /");
            exit();
        }
        $this->model->eliminarReportada($_GET['id']);
        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "La pregunta reportada se ha rechazado con exito";
        header("location: /");
    }
}
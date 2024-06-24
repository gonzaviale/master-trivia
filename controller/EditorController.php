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
        $this->presenter->render("view/indexEditorView.mustache");
    }

}
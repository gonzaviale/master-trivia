<?php

namespace controller;

class SugerirPreguntaController
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
        $this->presenter->render("view/sugerirPreguntaView.mustache");
    }

    public function agregarPreguntaSugerida(){
        session_start();
        $categoria_id = $_POST['categoria'];
        $pregunta = $_POST['pregunta_sugerida'];
        $respuesta_correcta = $_POST['respuesta_correcta'];
        $incorrecta_1 = $_POST['incorrecta_1'];
        $incorrecta_2 = $_POST['incorrecta_2'];
        $incorrecta_3 = $_POST['incorrecta_3'];

        $this->model->agregarPreguntaSugerida($categoria_id, $pregunta, $respuesta_correcta, $incorrecta_1, $incorrecta_2, $incorrecta_3);

        if (empty($categoria_id) || empty($pregunta) || empty($respuesta_correcta) || empty($incorrecta_1) || empty($incorrecta_2) || empty($incorrecta_3)) {
            // Mostrar un mensaje de error
            $error = "Todos los campos son obligatorios.";
            return $this->presenter->render("view/sugerirPreguntaView.mustache", ['error' => $error]);
        }

        if(isset($_SESSION['mensaje']))
            unset($_SESSION['mensaje']);
        $_SESSION['mensaje'] = "Pregunta sugerida con exito";
        header("location: /");

    }
}
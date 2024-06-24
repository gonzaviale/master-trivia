<?php

namespace controller;
class RankingController
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
        if(!isset($_SESSION['username'])){
            header("location: /");
            exit();
        }
            $rankingJugadores = $this->model->mejores10Jugadores();

            $data = [];
            foreach ($rankingJugadores as $jugador) {
                $data[] = [
                    'fotoPerfil' => $jugador['foto_perfil'],
                    'nombreJugador' => $jugador['nombre_usuario'],
                    'puntajeJugador' => $jugador['puntaje']
                ];
            }

            $this->presenter->render("view/rankingView.mustache", ['jugadores' => $data]);
        }

}
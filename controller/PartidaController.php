<?php

class PartidaController{

    private $presenter;
    private $model;
    private $preguntas;

    public function __construct($presenter, $model)
    {
        session_start();
        $this->presenter = $presenter;
        $this->model = $model;
        $this->preguntas = $this->model->buscarPreguntas();
    }

    public function comenzarPartida()
    {
        if(!isset($_SESSION['username'])){
            header("location: /");
            exit();
        }
        $_SESSION['puntos'] = 0;
        unset($_SESSION['yaTermino']);
        unset($_SESSION['respuestaCorrectaFinal']);
        unset($_SESSION['puntajeFinal']);
        header("location: /partida/siguientePregunta");
    }

    public function jugarPartida()
    {
        if (isset($_SESSION['yaTermino']))
        {
            header("location:/");
        }
        $pregunta = $_SESSION['pregunta'] ?? $this->model->buscarPreguntaSinResponder();
        $_SESSION['pregunta'] = $pregunta;
        $respuestas = $this->model->buscarRespuestas($_SESSION['pregunta']['id']);
        $categoriaStyle = $this->model->buscarColorPorCategoria($_SESSION['pregunta']['categoria_id']);
        // Desordenar el array $respuestas
        shuffle($respuestas);
        $data = [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas
        ];
        return $this->presenter->render("view/jugarPartida.mustache", ['pregunta' => $data['pregunta'],
            'respuestas' => $data['respuestas'], 'categoriaStyle' => $categoriaStyle]);
    }

    public function procesarRespuesta()
    {
        if(!isset($_SESSION['username'])){
            header("location: /");
            exit();
        }
        echo '<script>
                localStorage.removeItem("endTime");
              </script>';
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $respuestaId = $_GET['respuesta'];
            if ($this->model->devolverSiEsCorrecta($respuestaId)) {
                $this->model->agregarCorrectaAJugador($_SESSION['username']);
                $this->model->agregarCorrectaAPregunta($_SESSION['pregunta']['id']);
                $_SESSION['puntos']++;
                unset($_SESSION['pregunta']);
                unset($_SESSION['yaTermino']);
                header("location:/partida/siguientePregunta");
            } else {
                $preguntaId = $_SESSION['pregunta']['id'];
                $username = $_SESSION['username'];
                $this->model->agregarIncorrectaAJugador($username);
                $this->model->agregarIncorrectaAPregunta($preguntaId);
                $fechaHora = $this->obtenerFechaHora();
                $this->model->guardarPartida($preguntaId, $username, $fechaHora, $_SESSION['puntos']);
                header("location:/partida/finalizarPartida");
            }
        } else {
            $preguntaId = $_SESSION['pregunta']['id'];
            $username = $_SESSION['username'];
            $this->model->agregarIncorrectaAJugador($username);
            $this->model->agregarIncorrectaAPregunta($preguntaId);
            $fechaHora = $this->obtenerFechaHora();
            $this->model->guardarPartida($preguntaId, $username, $fechaHora, $_SESSION['puntos']);
            header("location:/partida/finalizarPartida");
        }
    }

    public function siguientePregunta()
    {
        if(!isset($_SESSION['username'])){
            header("location: /");
            exit();
        }
        if(!isset($_SESSION['pregunta']))
        {
            echo '<script>
                localStorage.removeItem("endTime");
              </script>';
        }
        if (isset($_SESSION['yaTermino']))
        {
            header("location:/");
        }
        $this->jugarPartida();
    }

    public function reportarPregunta()
    {
        if(!isset($_SESSION['username'])){
            header("location: /");
            exit();
        }
        $this->model->agregarPreguntaReportada($_SESSION['username'], $_GET['idPregunta']);
        $this->jugarPartida();
    }

    public function finalizarPartida()
    {
        if(!isset($_SESSION['username'])){
            header("location: /");
            exit();
        }
        $_SESSION['yaTermino'] = "si";
        $idPregunta = $_SESSION['pregunta']['id'];
        if(!isset($_SESSION['pregunta']))
            header("location:/");
        if(isset($_SESSION['pregunta']['id']))
        {
            unset($_SESSION['pregunta']);
            unset($_SESSION['puntos']);
        }
        $data = $this->model->finalizarPartida($idPregunta, $_SESSION['username']);
        return $this->presenter->render("view/partidaFinalizada.mustache", ['respuestaCorrectaFinal' => $data['respuestaCorrectaFinal'],
            'puntajeFinal' => $data['puntajeFinal']]);
    }

    private function obtenerFechaHora() {
        // Establecer la zona horaria (UTC-3)
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        return date('Y-m-d H:i:s');
    }

}

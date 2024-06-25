<?php

namespace controller;
use Exception;
use helper\GraphicGenerator;


class AdministradorController
{
    private $presenter;
    private $pdfCreator;
    private $model;
    private $graphicGenerator;


    public function __construct($presenter, $model, $pdfCreator, $graphicGenerator)
    {
        session_start();
        $this->graphicGenerator = $graphicGenerator;
        $this->presenter = $presenter;
        $this->model = $model;
        $this->pdfCreator = $pdfCreator;
    }

    public function get()
    {



        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $this->presenter->render("view/adminView.mustache");
        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }

    public function obtenerDatosGenerales($range = 'anio')
    {

        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $cantidadJugadoresNuevos = $this->model->cantidadDeJugadoresNuevos($range);

            $cantidadPartidas = $this->model->cantidadDePartidas($range);

            $cantidadPreguntas = $this->model->cantidadDePreguntas($range);

            $data = [
                [
                    'descripcion' => 'Cantidad de Jugadores Nuevos',
                    'valor' => $cantidadJugadoresNuevos
                ],
                [
                    'descripcion' => 'Cantidad de Partidas',
                    'valor' => $cantidadPartidas
                ],
                [
                    'descripcion' => 'Cantidad de Preguntas',
                    'valor' => $cantidadPreguntas
                ]
            ];

            return $data;
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function obtenerDatosDeUsuarioPorSexo($range = 'anio')
    {

        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $cantidadUsuariosPorSexo = $this->model->cantidadDeUsuariosPorSexo();

            $data = [];
            foreach ($cantidadUsuariosPorSexo as $cantidad) {
                $data[] = [

                    'cantidadUsuariosPorSexo' => $cantidad['cantidadUsuariosPorSexo'],
                    'sexo' => $cantidad['sexo'],

                ];
            }
            return $data;

        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function obtenerDatosDeUsuarioPorPais($range = 'anio')
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $cantidadUsuariosPorPais = $this->model->cantidadUsuariosPorPaises($range);

            $data = [];
            foreach ($cantidadUsuariosPorPais as $cantidad) {
                $data[] = [

                    'cantidadUsuariosPorPais' => $cantidad['cantidadUsuariosPorPais'],
                    'pais' => $cantidad['pais'],

                ];
            }
            return $data;

        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function obtenerDatosDeUsuarioPorGrupoDeEdad($range = 'anio')
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $cantidadUsuariosPorGrupoDeEdad = $this->model->cantidadDeUsuariosPorGrupoDeEdad($range);

            $data = [];
            foreach ($cantidadUsuariosPorGrupoDeEdad as $cantidad) {
                $data[] = [

                    'cantidadUsuariosPorGrupoDeEdad' => $cantidad['cantidadUsuariosPorGrupoDeEdad'],
                    'grupoEdad' => $cantidad['grupoEdad'],

                ];
            }
            return $data;
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function mostrarDatosUsuarioPorPais()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosDeUsuarioPorPais();
            return $this->presenter->render("view/datosUsuarioPaisView.mustache", ['datosUsuario' => $data]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function mostrarDatosUsuarioPorSexo()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosDeUsuarioPorSexo();
            return $this->presenter->render("view/datosUsuarioSexoView.mustache", ['datosUsuario' => $data]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function mostrarDatosUsuarioPorGrupoDeEdad()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosDeUsuarioPorGrupoDeEdad();
            return $this->presenter->render("view/datosUsuarioEdadView.mustache", ['datosUsuario' => $data]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }


    public function mostrarDatosGenerales()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosGenerales();
            return $this->presenter->render("view/datosGeneralesView.mustache", ['datosUsuario' => $data]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }


    public function crearPDF()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $categoria = filter_input(INPUT_GET, 'categoria', FILTER_SANITIZE_STRING);
            $isGeneratingPDF = filter_input(INPUT_POST, 'generatingPDF', FILTER_VALIDATE_BOOLEAN);

            $data = [];
            $template = '';

            switch ($categoria) {
                case 'pais':
                    $data = $this->obtenerDatosDeUsuarioPorPais();
                    $template = 'view/datosUsuarioPaisView.mustache';
                    break;
                case 'sexo':
                    $data = $this->obtenerDatosDeUsuarioPorSexo();
                    $template = 'view/datosUsuarioSexoView.mustache';
                    break;
                case 'edad':
                    $data = $this->obtenerDatosDeUsuarioPorGrupoDeEdad();
                    $template = 'view/datosUsuarioEdadView.mustache';
                    break;
                default:
                    $data = $this->obtenerDatosGenerales();
                    $template = 'view/datosGeneralesView.mustache';
                    break;
            }


            $html = $this->presenter->generateHtml($template, ['datosUsuario' => $data, 'isPDF' => $isGeneratingPDF]);


            $this->pdfCreator->create($html);
        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }


    public function crearGraficoPDF()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $range = filter_input(INPUT_GET, 'range', FILTER_SANITIZE_STRING) ?? 'anio';


            $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

            if ($categoria == 'sexo') {
                $data = $this->obtenerDatosDeUsuarioPorSexo($range);
                $labels = array_column($data, 'sexo');
                $values = array_column($data, 'cantidadUsuariosPorSexo');
            } else if ($categoria == 'pais') {
                $data = $this->obtenerDatosDeUsuarioPorPais($range);
                $labels = array_column($data, 'pais');
                $values = array_column($data, 'cantidadUsuariosPorPais');
            } else if ($categoria == 'edad') {
                $data = $this->obtenerDatosDeUsuarioPorGrupoDeEdad($range);
                $labels = array_column($data, 'grupoEdad');
                $values = array_column($data, 'cantidadUsuariosPorGrupoDeEdad');
            } else {
                $data = $this->obtenerDatosGenerales($range);
                $labels = array_column($data, 'descripcion'); // Usar las descripciones como etiquetas
                $values = array_column($data, 'valor');
            }

            $fileName = 'chart_' . date('YmdHis') . '.png';
            $filePath = 'public/img/' . $fileName;

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $this->graphicGenerator->renderChartView($labels, $values, $filePath);

            $imageData = base64_encode(file_get_contents($filePath));
            $html = '<img src="data:image/png;base64,' . $imageData . '" alt="Gráfico">';
            $this->pdfCreator->create($html);

        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }

    public function crearGrafico()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $range = filter_input(INPUT_GET, 'range', FILTER_SANITIZE_STRING) ?? 'anio';


            $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

            if ($categoria == 'sexo') {
                $data = $this->obtenerDatosDeUsuarioPorSexo($range);
                $labels = array_column($data, 'sexo');
                $values = array_column($data, 'cantidadUsuariosPorSexo');
            } else if ($categoria == 'pais') {
                $data = $this->obtenerDatosDeUsuarioPorPais($range);
                $labels = array_column($data, 'pais');
                $values = array_column($data, 'cantidadUsuariosPorPais');
            } else if ($categoria == 'edad') {
                $data = $this->obtenerDatosDeUsuarioPorGrupoDeEdad($range);
                $labels = array_column($data, 'grupoEdad');
                $values = array_column($data, 'cantidadUsuariosPorGrupoDeEdad');
            } else {
                $data = $this->obtenerDatosGenerales($range);
                $labels = array_column($data, 'descripcion'); // Usar las descripciones como etiquetas
                $values = array_column($data, 'valor');
            }


            $this->graphicGenerator->renderChartViewOnSite($labels, $values);


        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }
}
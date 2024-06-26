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
    public function obtenerDatosDeUsuarioPorPorcentaje()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $porcentajes = $this->model->porcentajeJugador();

            $data = [];
            foreach ($porcentajes as $porcentaje) {
                $data[] = [

                    'porcentaje' => $porcentaje['porcentaje_correctas'] . '%',
                    'nombre' => $porcentaje['nombre_completo'],

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
            $dataAnio = $this->obtenerDatosDeUsuarioPorPais('anio');
            $dataMes = $this->obtenerDatosDeUsuarioPorPais('mes');
            $dataDia = $this->obtenerDatosDeUsuarioPorPais('dia');
            $graficoAnio = $this->crearGrafico($dataAnio, 'pais','anio');
            $graficoMes = $this->crearGrafico($dataMes, 'pais','mes');
            $graficoDia = $this->crearGrafico($dataDia, 'pais','dia');

            return $this->presenter->render("view/datosUsuarioPaisView.mustache", [
                'datosUsuario' => $data,
                'graficoDia' => $graficoDia,
                'graficoMes' => $graficoMes,
                'graficoAnio' => $graficoAnio
            ]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }

    public function mostrarDatosPorcentaje()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosDeUsuarioPorPorcentaje();

            return $this->presenter->render("view/datosPorcentajeUsuario.mustache", [
                'datosUsuario' => $data,

            ]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }
    public function mostrarDatosUsuarioPorSexo()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosDeUsuarioPorSexo();
            $dataAnio = $this->obtenerDatosDeUsuarioPorSexo('anio');
            $dataMes = $this->obtenerDatosDeUsuarioPorSexo('mes');
            $dataDia = $this->obtenerDatosDeUsuarioPorSexo('dia');

            $graficoAnio = $this->crearGraficoTorta($dataAnio, 'sexo','anio');
            $graficoMes = $this->crearGraficoTorta($dataMes, 'sexo','mes');
            $graficoDia = $this->crearGraficoTorta($dataDia, 'sexo','dia');

            return $this->presenter->render("view/datosUsuarioSexoView.mustache", [
                'datosUsuario' => $data,
                'graficoAnio' => $graficoAnio,
                'graficoMes' => $graficoMes,
                'graficoDia' => $graficoDia,
            ]);
        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }



    public function mostrarDatosUsuarioPorGrupoDeEdad()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosDeUsuarioPorGrupoDeEdad();
            $dataAnio = $this->obtenerDatosDeUsuarioPorGrupoDeEdad('anio');
            $dataMes = $this->obtenerDatosDeUsuarioPorGrupoDeEdad('mes');
            $dataDia = $this->obtenerDatosDeUsuarioPorGrupoDeEdad('dia');

            $graficoAnio = $this->crearGrafico($dataAnio, 'edad','anio');
            $graficoMes = $this->crearGrafico($dataMes, 'edad','mes');
            $graficoDia = $this->crearGrafico($dataDia, 'edad','dia');
            return $this->presenter->render("view/datosUsuarioEdadView.mustache", [
                'datosUsuario' => $data,
                'graficoAnio' => $graficoAnio,
                'graficoMes' => $graficoMes,
                'graficoDia' => $graficoDia,
            ]);

        } else {
            return $this->presenter->render("view/registroView.mustache");
        }
    }


    public function mostrarDatosGenerales()
    {
        if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
            $data = $this->obtenerDatosGenerales();
            $dataAnio = $this->obtenerDatosGenerales('anio');
            $dataMes = $this->obtenerDatosGenerales('mes');
            $dataDia = $this->obtenerDatosGenerales('dia');

            $graficoAnio = $this->crearGrafico($dataAnio, 'general', 'anio');
            $graficoMes = $this->crearGrafico($dataMes, 'general', 'mes');
            $graficoDia = $this->crearGrafico($dataDia, 'general', 'dia');

            return $this->presenter->render("view/datosGeneralesView.mustache", [
                'datosUsuario' => $data,
                'graficoAnio' => $graficoAnio,
                'graficoMes' => $graficoMes,
                'graficoDia' => $graficoDia,
            ]);
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
                    $template = 'view/imprimirPDFView.mustache';
                    break;
                case 'sexo':
                    $data = $this->obtenerDatosDeUsuarioPorSexo();
                    $template = 'view/imprimirPDFView.mustache';
                    break;
                case 'edad':
                    $data = $this->obtenerDatosDeUsuarioPorGrupoDeEdad();
                    $template = 'view/imprimirPDFView.mustache';
                    break;
                case 'general':
                    $data = $this->obtenerDatosGenerales();
                    $template = 'view/imprimirPDFView.mustache';
                    break;
                case 'porcentaje':
                    $data = $this->obtenerDatosDeUsuarioPorPorcentaje();
                    $template = 'view/imprimirPDFView.mustache';
                    break;
            }


            $html = $this->presenter->generateHtml($template, ['datosUsuario' => $data, 'isPDF' => $isGeneratingPDF]);


            $this->pdfCreator->create($html);
        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }



        public function crearGrafico($data, $categoria, $rango)
        {
            if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
                $labels = [];
                $values = [];

                switch ($categoria) {

                    case 'pais':
                        $labels = array_column($data, 'pais');
                        $values = array_column($data, 'cantidadUsuariosPorPais');
                        break;
                    case 'edad':
                        $labels = array_column($data, 'grupoEdad');
                        $values = array_column($data, 'cantidadUsuariosPorGrupoDeEdad');
                        break;
                    case 'general':
                        $labels = array_column($data, 'descripcion');
                        $values = array_column($data, 'valor');
                        break;
                }

                // Generar el gráfico y obtener su HTML
                $fileName = 'chart_' . $categoria . '_' . $rango . '_' . date('YmdHis') . '.png';
                $filePath = 'public/img/' . $fileName;

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->graphicGenerator->renderChartView($labels, $values, $filePath);


                $imageData = base64_encode(file_get_contents($filePath));
                $imgSrc = 'data:image/png;base64,' . $imageData;


                $html = '<img src="' . $imgSrc . '" alt="Gráfico ' . ucfirst($categoria) . ' ' . ucfirst($rango) . '">';

                return $html;
            } else {

                return '';
            }
        }
            public function crearGraficoTorta($data, $categoria, $rango)
        {
            if (isset($_SESSION['username']) && $_SESSION['rol'] == 'Administrador') {
                $labels = [];
                $values = [];

                switch ($categoria) {
                    case 'sexo':
                        $labels = array_column($data, 'sexo');
                        $values = array_column($data, 'cantidadUsuariosPorSexo');
                        break;

                }

                // Generar el gráfico y obtener su HTML
                $fileName = 'chart_' . $categoria . '_' . $rango . '_' . date('YmdHis') . '.png';
                $filePath = 'public/img/' . $fileName;

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->graphicGenerator->renderPieChartView($labels, $values, $filePath);


                $imageData = base64_encode(file_get_contents($filePath));
                $imgSrc = 'data:image/png;base64,' . $imageData;


                $html = '<img src="' . $imgSrc . '" alt="Gráfico ' . ucfirst($categoria) . ' ' . ucfirst($rango) . '">';

                return $html;
            } else {

                return '';
            }
    }





}
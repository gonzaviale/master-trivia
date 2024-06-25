<?php

namespace controller;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require 'vendor/autoload.php';

class PerfilController
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
            $nombreUsuario = $_GET['usuario'];

            $perfilDeUsuario = $this->model->mostrarPerfil($nombreUsuario);
            $data = [];
            foreach ($perfilDeUsuario as $perfil) {
                if(!isset($perfil['partidasJugadas']))
                {
                    $data[] = [
                        'cantidadPartidas' => 0,
                        'fotoPerfil' => $perfil['foto_perfil'],
                        'nombreUsuario' => $perfil['nombre_usuario'],
                        'puntajeAcumulado' => 0,
                        'pais' => $perfil['pais'],
                        'ciudad' => $perfil['ciudad']
                    ];
                } else
                {
                    $data[] = [
                        'cantidadPartidas' => $perfil['partidasJugadas'],
                        'fotoPerfil' => $perfil['foto_perfil'],
                        'nombreUsuario' => $perfil['nombre_usuario'],
                        'puntajeAcumulado' => $perfil['puntaje_acumulado'],
                        'pais' => $perfil['pais'],
                        'ciudad' => $perfil['ciudad']
                    ];
                }

            }

            $ciudad = $perfilDeUsuario[0]['ciudad'];
            $pais = $perfilDeUsuario[0]['pais'];

            $script = "<script>window.onload = function() { buscarUbicacionJugador('$ciudad', '$pais'); };</script>";

            $urlPerfil = "http://localhost/perfil/get/usuario=" . $nombreUsuario;

            // Generate QR code
            $qrCode = QrCode::create($urlPerfil)
                ->setEncoding(new Encoding('UTF-8'))
                ->setSize(300)
                ->setMargin(10);

            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode)->getString();
            $qrCodeImageBase64 = 'data:image/png;base64,' . base64_encode($qrCodeImage);

            $this->presenter->render("view/perfilJugadorView.mustache", [
                'perfiles' => $data,
                'script' => $script,
                'qr' => $qrCodeImageBase64
            ]);
    }
}

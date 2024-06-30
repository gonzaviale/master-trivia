<?php

namespace helper;
use Mustache_Autoloader;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class MustachePresenter
{
    private $mustache;
    private $partialsPathLoader;

    public function __construct($partialsPathLoader)
    {
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
                'partials_loader' => new Mustache_Loader_FilesystemLoader($partialsPathLoader)
            ));
        $this->partialsPathLoader = $partialsPathLoader;
    }

    public function render($contentFile, $data = array())
    {
        echo $this->generateHtml($contentFile, $data);
    }

    public function generateHtml($contentFile, $data = array())
    {
        $isImprimirPDFView = basename($contentFile) === 'imprimirPDFView.mustache';

        if (!$isImprimirPDFView) {
            if (isset($_SESSION['username'])) {
                $contentAsString = file_get_contents($this->partialsPathLoader . '/headerLogeado.mustache');
            } else {
                $contentAsString = file_get_contents($this->partialsPathLoader . '/header.mustache');
            }
            $contentAsString .= file_get_contents($contentFile);
        } else {
            $contentAsString = file_get_contents($contentFile);
        }

        return $this->mustache->render($contentAsString, $data);

    }
}
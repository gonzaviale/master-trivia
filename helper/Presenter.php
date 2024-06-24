<?php

namespace helper;
class Presenter
{

    public function __construct()
    {
    }

    public function render($view, $data = [])
    {
        if (isset($_SESSION['username'])) {
            include_once("view/template/headerLogeado.mustache");
            include_once($view);
        } else {
            include_once("view/template/header.mustache");
            include_once($view);
        }
    }
}
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class UsuarioController
{
    private $presenter;

    public function __construct($presenter)
    {
        $this->presenter = $presenter;
    }

    public function get()
    {
        session_start();
        if(isset($_SESSION['username'])){
            $this->presenter->render("view/indexView.mustache");
        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }
    public function logout()
    {
        session_start();
        session_destroy();
        header("location:/");
        exit();
    }
}
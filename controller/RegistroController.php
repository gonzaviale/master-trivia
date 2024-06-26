<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class RegistroController
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
        if(isset($_SESSION['username'])){
            $this->presenter->render("view/indexView.mustache");
        } else {
            $this->presenter->render("view/registroView.mustache");
        }
    }

    /**
     * @throws \Random\RandomException
     */
    public function procesarRegistro()
    {
        $nombreCompleto = $_POST['nombreCompleto'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $anioNacimiento = $_POST['anioNacimiento'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $pais = $_POST['pais'] ?? '';

        $email = $_POST['email'] ?? '';
        $contrasenia = $_POST['contrasenia'] ?? '';
        $repetirContrasenia = $_POST['repetirContrasenia'] ?? '';
        $nombreUsuario = $_POST['nombreUsuario'] ?? '';

        $directorio_destino = 'public/img/';
        if(empty($_FILES['fotoPerfil']))
        {
            $error = "Todos los campos son obligatorios.";
            return $this->presenter->render("view/registroView.mustache", ['error' => $error]);
        }

        $nombre_archivo = $_FILES['fotoPerfil']['name'];
        $ruta_archivo_destino = $directorio_destino . $nombreUsuario . $nombre_archivo;

        if (empty($nombreCompleto) || empty($anioNacimiento) || empty($sexo) || empty($pais) || empty($email) || empty($contrasenia) || empty($repetirContrasenia) ||
            empty($nombreUsuario)|| empty($ciudad) || empty($nombre_archivo)) {
            // Mostrar un mensaje de error
            $error = "Todos los campos son obligatorios.";
            return $this->presenter->render("view/registroView.mustache", ['error' => $error]);
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $error = "El correo electrónico ingresado no es válido.";
            return $this->presenter->render("view/registroView.mustache", ['error' => $error]);
        }


        if ($contrasenia != $repetirContrasenia) {

            $error = "Las contraseñas no coinciden.";
            return $this->presenter->render("view/registroView.mustache", ['error' => $error]);
        }


        if ($this->model->buscarUsuario($email, $nombreUsuario)) {

            $error = "El usuario ya existe.";
            return $this->presenter->render("view/registroView.mustache", ['error' => $error]);
        }

        move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $ruta_archivo_destino);

        $this->agregarUsuario($email, $nombreCompleto, $anioNacimiento, $sexo, $pais, $contrasenia, $nombreUsuario, $ruta_archivo_destino,$ciudad);


        $token = bin2hex(random_bytes(16));


        $this->model->guardarTokenValidacion($email, $token);


        $this->enviarCorreoValidacion($email, $token);

        return $this->presenter->render("view/verificacionEmailView.mustache");

    }

    public function validarCuenta()
    {
        $token = $_GET['token'];


        $usuario = $this->model->buscarUsuarioPorToken($token);

        if (!empty($usuario)) {
            $this->model->validarCuenta($token);
            return $this->presenter->render("view/verificacionExitosaView.mustache");

        } else {
            $error = "El token de validación no es válido.";
            return $this->presenter->render("view/registroView.mustache", ['error' => $error]);
        }
    }

    private function agregarUsuario($email,$nombreCompleto,$anioNacimiento,$sexo,$pais,$contrasenia,$nombreUsuario,$foto,$ciudad)
    {
        $this->model->agregarUsuario($email,$nombreCompleto,$anioNacimiento,$sexo,$pais,$contrasenia,$nombreUsuario,$foto,$ciudad);
    }

    private function enviarCorreoValidacion($email, $token)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //Server settings
            //->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'llombartkevin@gmail.com';                     //SMTP username
            $mail->Password = 'pewf edmk dhzt mbyc';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('llombartkevin@gmail.com', 'Preguntados');
            $mail->addAddress($email);     //Add a recipient
            //Name is optional

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Validacion de cuenta';
            $mail->Body =" Hola,<br><br>Por favor haz clic en el siguiente enlace para validar tu cuenta:<br><a href='http://localhost/registro/validarCuenta/token=$token'>Validar cuenta</a>";


            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

}

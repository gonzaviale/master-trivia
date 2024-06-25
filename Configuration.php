<?php

use controller\AdministradorController;
use controller\LoginController;
use controller\PerfilController;
use controller\RankingController;
use controller\SugerirPreguntaController;
use controller\EditorController;
use controller\AgregarPreguntaEditorController;
use controller\ModificarPreguntaEditorController;
use controller\EliminarPreguntaEditorController;
use controller\RevisarSugeridasEditorController;
use controller\RevisarReportadasEditorController;
use helper\Database;
use helper\MustachePresenter;
use helper\Router;
use helper\GraphicGenerator;
use model\PartidaModel;
use model\RankingModel;
use model\RegistroModel;
use model\UsuarioModel;

include_once("controller/UsuarioController.php");
include_once("controller/PartidaController.php");
include_once("controller/LoginController.php");
include_once("controller/RegistroController.php");
include_once("controller/RankingController.php");
include_once("controller/PerfilController.php");
include_once("controller/SugerirPreguntaController.php");
include_once("controller/EditorController.php");
include_once("controller/AgregarPreguntaEditorController.php");
include_once("controller/ModificarPreguntaEditorController.php");
include_once("controller/EliminarPreguntaEditorController.php");
include_once("controller/RevisarSugeridasEditorController.php");
include_once("controller/RevisarReportadasEditorController.php");
include_once("controller/AdministradorController.php");


include_once("model/LoginModel.php");
include_once("model/RegistroModel.php");
include_once("model/PartidaModel.php");
include_once("model/RankingModel.php");
include_once("model/UsuarioModel.php");
include_once("model/SugerirPreguntaModel.php");
include_once("model/EditorModel.php");
include_once("model/AgregarPreguntaEditorModel.php");
include_once("model/ModificarPreguntaEditorModel.php");
include_once("model/EliminarPreguntaEditorModel.php");
include_once("model/RevisarSugeridasEditorModel.php");
include_once("model/RevisarReportadasEditorModel.php");
include_once("model/AdministradorModel.php");


include_once ("helper/Database.php");
include_once ("helper/Router.php");
include_once ("helper/PDF.php");
include_once ("helper/GraphicGenerator.php");

include_once ("helper/Presenter.php");
include_once ("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{

    // CONTROLLERS
    public static function getUsuarioController()
    {
        return new UsuarioController(self::getPresenter());
    }

    public static function getAdministradorController()
    {
        return new AdministradorController(self::getPresenter(),self::getAdministradorModel(),self::getPDF(),self::getGraphicGenerator());
    }

    public static function getPerfilController()
    {
        return new PerfilController(self::getPresenter(),self::getUsuarioModel());
    }

    public static function getLoginController()
    {
        return new LoginController(self::getPresenter(),self::getLoginModel());
    }

    public static function getRankingController()
    {
        return new RankingController(self::getPresenter(),self::getRankingModel());
    }

    public static function getRegistroController()
    {
        return new RegistroController(self::getPresenter(),self::getRegistroModel());
    }

    public static function getPartidaController()
    {
        return new PartidaController(self::getPresenter(),self::getPartidaModel());
    }

    public static function getSugerirPreguntaController()
    {
        return new SugerirPreguntaController(self::getPresenter(),self::getSugerirPreguntaModel());
    }

    public static function getEditorController()
    {
        return new EditorController(self::getPresenter(),self::getEditorModel());
    }

    public static function getAgregarPreguntaEditorController()
    {
        return new AgregarPreguntaEditorController(self::getPresenter(),self::getAgregarPreguntaEditorModel());
    }

    public static function getModificarPreguntaEditorController()
    {
        return new ModificarPreguntaEditorController(self::getPresenter(),self::getModificarPreguntaEditorModel());
    }

    public static function getEliminarPreguntaEditorController()
    {
        return new EliminarPreguntaEditorController(self::getPresenter(),self::getEliminarPreguntaEditorModel());
    }

    public static function getRevisarSugeridasEditorController()
    {
        return new RevisarSugeridasEditorController(self::getPresenter(),self::getRevisarSugeridasEditorModel());
    }

    public static function getRevisarReportadasEditorController()
    {
        return new RevisarReportadasEditorController(self::getPresenter(),self::getRevisarReportadasEditorModel());
    }

    // MODELS
    public static function getLoginModel()
    {
        return new LoginModel(self::getDatabase());
    }

    public static function getAdministradorModel()
    {
        return new AdministradorModel(self::getDatabase());
    }

    public static function getUsuarioModel()
    {
        return new UsuarioModel(self::getDatabase());
    }

    public static function getRankingModel()
    {
        return new RankingModel(self::getDatabase());
    }

    public static function getPartidaModel()
    {
        return new PartidaModel(self::getDatabase());
    }

    public static function getRegistroModel()
    {
        return new RegistroModel(self::getDatabase());
    }



    public static function getSugerirPreguntaModel()
    {
        return new SugerirPreguntaModel(self::getDatabase());
    }

    public static function getEditorModel()
    {
        return new SugerirPreguntaModel(self::getDatabase());
    }

    public static function getAgregarPreguntaEditorModel()
    {
        return new AgregarPreguntaEditorModel(self::getDatabase());
    }

    public static function getModificarPreguntaEditorModel()
    {
        return new ModificarPreguntaEditorModel(self::getDatabase());
    }

    public static function getEliminarPreguntaEditorModel()
    {
        return new EliminarPreguntaEditorModel(self::getDatabase());
    }

    public static function getRevisarSugeridasEditorModel()
    {
        return new RevisarSugeridasEditorModel(self::getDatabase());
    }

    public static function getRevisarReportadasEditorModel()
    {
        return new RevisarReportadasEditorModel(self::getDatabase());
    }

    // HELPERS
    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private static function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

    private static function getPDF()
    {
        return new PDF();
    }
    private static function getGraphicGenerator()
    {
        return new GraphicGenerator();
    }


    public static function getRouter()
    {
        return new Router("getLoginController", "get");
    }

    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}
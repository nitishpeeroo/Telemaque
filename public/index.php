<?php

//Définition du chemin vers le répertoire application
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

//Définition de la variable d'environnement
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//Emplacement de la librairie Zend
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/configs'),
    get_include_path(),
)));

/** Zend_Application **/
require_once 'Zend/Application.php';
require_once APPLICATION_PATH . '/configs/const.inc';

//Creation de l'application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
$application->bootstrap()->run();
<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    
   /**
    * Initialize Router
    * @return unknown_type
    */

   protected function _initRouter(){
       $this->bootstrap('frontController');
       $front = $this->getResource('frontController');
       $router = $front->getRouter();
       $router->addConfig(new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'routes'), 'routes');
       $router->addDefaultRoutes();
       return $router;
   }
}
<?php

class IndexController extends Zend_Controller_Action {

    public function indexAction()   {
              session_start();
        $this->view->headTitle('Accueil');
        
        if(isset($_SESSION['user']))
        {
        
            $this->view->firstname = $_SESSION['user']['firstname_user'];
            $this->view->lastname = $_SESSION['user']['lastname_user'];
            $this->view->rank = $_SESSION['user']['label_rank'];
            $this->view->lvl = $_SESSION['user']['id_rank'];
        }
    }
}
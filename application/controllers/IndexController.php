<?php

class IndexController extends Zend_Controller_Action {

    public function indexAction()   {
        zend_session::start(); 
        $this->view->headTitle('Accueil');
        
        $ns = new Zend_Session_Namespace('user');
        
        if(!empty($ns->data))
        {    
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->lvl = $ns->data['id_rank'];
        }
    }
}
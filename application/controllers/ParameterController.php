<?php
    class ParameterController extends Zend_Controller_Action {

        public function init() {
             zend_session::start();
            parent::init();
           
        }

        public function indexAction() {
            $this->view->headTitle('Gestion utilisateur');
            
            $ns = new Zend_Session_Namespace('user');
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->mail = $ns->data['mail_user'];
            $this->view->phone = $ns->data['phone_user'];
            $this->view->address = $ns->data['address_user'];
        }
        
        public function modifyAction()
        {
            
        }

    }

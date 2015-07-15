<?php

class UserController extends Zend_Controller_Action {

    public function init() {

        parent::init();
    }

    public function indexAction() {
        session_start();
        $this->view->form = new Application_Form_UserConnection();
        if ($this->_request->isPost()) {
            
            //sign in
            if ($_POST['type'] = 1) {

                $login = $_POST['login'];
                $mdp = $_POST['password'];
                $user = new Application_Model_User();
                $connection = $user->login($login, $mdp);
//                var_dump($connection);die();
                if (!isset($connection['id_user'])) {
                    echo "vous n'exister pas";
                } else {
                
                    $_SESSION['user'] = $connection;
                    
                    $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index'),null,true));
                }
            // sign up
            } elseif ($_POST['type'] == 2) {

                $login = $_POST['login'];
                $mdp = $_POST['password'];
                $firstname = $_POST['first_name'];
                $lastname = $_POST['last_name'];
                $mail = $_POST['email'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $user = new Application_Model_User();
                $stat = $user->inscription($login, $mdp, $firstname, $lastname, $mail, $phone, $address);
                if ($stat == true) {
                    echo "vous êtes inscrit";
                } else {
                    echo "erreur lors de l'enregistrement";
                }
            }
        }
    }
    
    public function destroyAction(){
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
            session_destroy();
        }
           $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index'),null,true));
    }

}
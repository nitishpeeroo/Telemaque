<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        
        parent::init();
        zend_session::start();
        
        $ns = new Zend_Session_Namespace('user');
        
        if(!empty($ns->data))
        {    
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->lvl = $ns->data['id_rank'];
        }
    }

    public function indexAction() 
    {    
        if($this->_getParam('login') == true)
        {
            
            echo "<script>alert('Ce login existe déjà.');</script>";
            
        }
        
        if ($this->_request->isPost()) {         
            //sign in
            if ($this->_getParam('type') == 'signin') {
                
                $login = $_POST['login'];
                $mdp = $_POST['password'];
                
                $user = new Application_Model_User();
                $connection = $user->login($login, $mdp);
                if (!isset($connection['id_user'])) {
                    echo "vous n'exister pas";
                } else {
                    $this -> sess = new Zend_Session_Namespace('user');
                    $this -> sess->data = $connection;
                    
                    $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index'),null,true));
                }
            // sign up
            } elseif ($this->_getParam('type') == 'signup') {
                
                $login = $_POST['login'];
                $mdp = $_POST['password'];
                $firstname = $_POST['first_name'];
                $lastname = $_POST['last_name'];
                $mail = $_POST['email'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $user = new Application_Model_User();
                $verif = $user->loginExist($login);
                if($verif > 0)
                {
                    $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'index','login'=>false), null, true));
                }
                $stat = $user->inscription($login, $mdp, $firstname, $lastname, $mail, $phone, $address);
                $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index'), null, true));
                if ($stat != -1) {
                    echo "vous êtes inscrit";
                } else {
                    echo "erreur lors de l'enregistrement";
                }
            }
        }
    }
    
    public function destroyAction() 
    {
        Zend_Session:: namespaceUnset("user");
        Zend_Session::destroy(true);
        $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'index'), null, true));
    }
    
    public function parameterAction()
    {
        $this->view->headTitle('Gestion u   tilisateur');
        
        $ns = new Zend_Session_Namespace('user');
        if($this->_getParam('type') == "apply")
        {                     
                $mdp = $_POST['password'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $mail = $_POST['mail'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];

                $user = new Application_Model_User();
                $stat = $user->updateUser($mdp, $firstname, $lastname, $mail, $phone, $address, $ns->data['id_user']);   
                $ns->data = $stat;

        }

        $this->view->firstname = $ns->data['firstname_user'];
        $this->view->lastname = $ns->data['lastname_user'];
        $this->view->mail = $ns->data['mail_user'];
        $this->view->phone = $ns->data['phone_user'];
        $this->view->address = $ns->data['address_user'];
    }
    
    public function modifyAction()
    {
        $this->view->headTitle('modification utilisateur');
            
        $ns = new Zend_Session_Namespace('user');
        $this->view->firstname = $ns->data['firstname_user'];
        $this->view->lastname = $ns->data['lastname_user'];
        $this->view->mail = $ns->data['mail_user'];
        $this->view->phone = $ns->data['phone_user'];
        $this->view->address = $ns->data['address_user'];
    }
}
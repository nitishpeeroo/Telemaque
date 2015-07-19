<?php

class UserController extends Zend_Controller_Action {

    public function init() {

        parent::init();
        zend_session::start();

        $ns = new Zend_Session_Namespace('user');

        if (!empty($ns->data)) {
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->lvl = $ns->data['id_rank'];
        }
    }

    public function indexAction() {
     
        if ($this->_getParam('login') == true) {

            echo "<script>alert('Ce login existe déjà.');</script>";
        }

        if ($this->_request->isPost()) {
//               require '../models/Recaptcha.php';
//        $captcha = new Recaptcha("6Lc9CgoTAAAAAFg8ewZxZ5SOjaPDm2htB7_QVtLz");
//        if ($captcha->checkCode($_POST['g-recaptcha-response']) == false) {
//            var_dump("sa merdouille");
//            die();
//        } else {
//            var_dump("sa gere");
//            die();
//        }
            //sign in
            if ($this->_getParam('type') == 'signin') {

                $login = $_POST['login'];
                $mdp = $_POST['password'];

                $user = new Application_Model_User();
                $connection = $user->login($login, $mdp);
                if (!isset($connection['id_user'])) {
                    echo "vous n'exister pas";
                } else {
                    $this->sess = new Zend_Session_Namespace('user');
                    $this->sess->data = $connection;

                    $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index'), null, true));
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
                if ($verif > 0) {
                    $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'index', 'login' => false), null, true));
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

    public function destroyAction() {
        Zend_Session:: namespaceUnset("user");
        Zend_Session::destroy(true);
        $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'index'), null, true));
    }

    public function parameterAction() {
        $this->view->headTitle('Gestion u   tilisateur');

        $ns = new Zend_Session_Namespace('user');
        if ($this->_getParam('type') == "apply") {
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

    public function modifyAction() {
        $this->view->headTitle('modification utilisateur');

        $ns = new Zend_Session_Namespace('user');
        $this->view->firstname = $ns->data['firstname_user'];
        $this->view->lastname = $ns->data['lastname_user'];
        $this->view->mail = $ns->data['mail_user'];
        $this->view->phone = $ns->data['phone_user'];
        $this->view->address = $ns->data['address_user'];
    }

    public function articleAction() {

        $ns = new Zend_Session_Namespace('user');
        $sell = new Application_Model_Sell();
        $images = new Application_Model_Image();
        $categorys = new Application_Model_Category();

        if ($this->_request->isPost()) {
            $title = $_POST['title'];

            // image data 
            $image = @file_get_contents($_FILES['image']['tmp_name']);
            $nameImage = $_FILES['image']['name'];

            // sell data
            $quantity = $_POST['quantity'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $descritptionCourt = $_POST['descritptionCourt'];
            $descritption = $_POST['descritption'];
            $idUser = $ns->data['id_user'];


            // recuperation de idSell après insertion
            $idSell = $sell->addSell($title, $image, $quantity, $category, $price, $descritptionCourt, $descritption, $idUser);


            // insertion de l'image grace a l'idSell
            $ResultImage = $images->addImage($idSell, $image, $nameImage);
            if ($ResultImage == true) {
                $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'article'), null, true));
            }
        }
        $this->view->category = $categorys->getRubrique();

        $this->view->souscategory = $categorys->getSousRubrique();

        $table = $sell->getUserSell($ns->data['id_user']);
        ;


        foreach ($table as &$value) {
            $value['image'] = base64_encode($value['image']);
            $value['name_image'] = pathinfo($table['name_image'], PATHINFO_EXTENSION);
        }

        $this->view->articles = $table;
    }

}

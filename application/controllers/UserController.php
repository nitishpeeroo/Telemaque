<?php

class UserController extends Zend_Controller_Action {

    public function init() {

        parent::init();
        zend_session::start();

        $ns = new Zend_Session_Namespace('user');
        $use = new Application_Model_User();
        $general = new Application_Model_General();
        
        $statUser = $general->veriStatUser($ns->data);
        if (!empty($ns->data)) {
                $this->view->firstname = $ns->data['firstname_user'];
                $this->view->lastname = $ns->data['lastname_user'];
                $this->view->lvl = $ns->data['id_rank'];
        }
        if($statUser == 1 OR $statUser == 2 )
        {
            $this->view->isadmin = $statUser;   
        }
        else if($statUser == 3)
        {  
           Zend_Session:: namespaceUnset("user");
           Zend_Session::destroy(true);
           $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'acces'), null, true));
        }
        if($this->_getParam('message') != null ){
            switch($this->_getParam('message')){
                case 'deconnection':
                    $this->view->message = "Vous êtes déconnecté";
                break;
                case 'done':
                    $this->view->message = "Un article à été ajouté";
                break;
                case 'connecter':
                    $this->view->message = "Vous êtes connecter";
                break;
            }
        }
    }

    public function indexAction() {
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
                   
                    if($connection['is_blocked'] == '0')
                    {
                    $this->sess = new Zend_Session_Namespace('user');
                    $this->sess->data = $connection;

                    $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index','message'=>'connecter'), null, true));
                    }
                    else
                    {
                        $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'acces'), null, true));
                    }
                    
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
                $cp = $_POST['code_postal'];
                $ville = $_POST['ville'];
                $user = new Application_Model_User();
                $verif = $user->loginExist($login);
                if ($verif > 0) {
                    $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'index', 'login' => false), null, true));
                }
                $stat = $user->inscription($login, $mdp, $firstname, $lastname, $mail, $phone, $address, $cp, $ville);
                $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'index'), null, true));
                if ($stat != -1) {
                     $this->view->message = "Vous êtes inscrit";
                } else {
                     $this->view->message = "Erreur lors de l'enregistrement";
                }
            }
        }
    }

    public function destroyAction() {
        Zend_Session:: namespaceUnset("user");
        Zend_Session::destroy(true);
        $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'index', 'message'=>'deconnection'), null, true));
    }

    public function parameterAction() {
        $ns = new Zend_Session_Namespace('user');
        if (empty($ns->data)) {
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'error', 'type' => 'page'), null, true));
        }
        $this->view->headTitle('Gestion utilisateur');

        $ns = new Zend_Session_Namespace('user');
        if ($this->_getParam('type') == "apply") {
            $mdp = $_POST['password'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $mail = $_POST['mail'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $ville = $_POST['ville'];
            $cp = $_POST['cp'];
            $lastMdp = $_POST['last_password'];

            $user = new Application_Model_User();

            if ($lastMdp != null) {
                $val = $user->verifMdp($lastMdp, $ns->data['id_user']);
                if ($val) {
                    $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'modify', 'type' => 'error_password'), null, true));
                }
            }
            $stat = $user->updateUser($mdp, $firstname, $lastname, $mail, $phone, $address, $ville, $cp, $ns->data['id_user']);
            $ns->data = $stat;
            $this->view->message = "Modification sauvegard�";
        }

        $this->view->firstname = $ns->data['firstname_user'];
        $this->view->lastname = $ns->data['lastname_user'];
        $this->view->mail = $ns->data['mail_user'];
        $this->view->phone = $ns->data['phone_user'];
        $this->view->address = $ns->data['address_user'];
        $this->view->ville = $ns->data['ville_user'];
        $this->view->cp = $ns->data['codepostal_user'];
    }

    public function modifyAction() {
        $ns = new Zend_Session_Namespace('user');
        if (empty($ns->data)) {
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'error', 'type' => 'page'), null, true));
        }

        if ($this->_getParam('type') == 'error_password') {
            $this->view->error = "<b style='color:red;'>Ancien mot de passe incorrect</b>";
        }
        $this->view->headTitle('modification utilisateur');

        $ns = new Zend_Session_Namespace('user');
        $this->view->firstname = $ns->data['firstname_user'];
        $this->view->lastname = $ns->data['lastname_user'];
        $this->view->mail = $ns->data['mail_user'];
        $this->view->phone = $ns->data['phone_user'];
        $this->view->address = $ns->data['address_user'];
        $this->view->cp = $ns->data['codepostal_user'];
        $this->view->ville = $ns->data['ville_user'];
    }

    public function articleAction() {
        $ns = new Zend_Session_Namespace('user');
        $sell = new Application_Model_Sell();
        $images = new Application_Model_Image();
        if (empty($ns->data)) {
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'error', 'type' => 'page'), null, true));
        }

        if ($this->_request->isPost()) {


            if ($this->_getParam('type') == 'ajout') {
                $title = $_POST['productTitle'];

                // image data 
                $image = @file_get_contents($_FILES['image']['tmp_name']);
                $nameImage = $_FILES['image']['name'];


                // sell data
                $quantity = $_POST['productQuantite'];
                $category = $_POST['sous-rubrique-hidden'];
                $price = $_POST['productPrix'];

                $descritptionCourt = $_POST['productDescritptionCourte'];
                $descritption = $_POST['descritption'];
                $idUser = $ns->data['id_user'];


                // recuperation de idSell après insertion
                $idSell = $sell->addSell($title, $quantity, $category, $price, $descritptionCourt, $descritption, $idUser);

                // insertion de l'image grace a l'idSell
                $ResultImage = $images->addImage($idSell, $image, $nameImage);

                if ($ResultImage == true) {
                    $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'article','message'=>'done'), null, true));
                }
            }
            if ($this->_getParam('type') == 'update') {
                $idSell = $_POST['idSell'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];
                $sell->updatePriceQuantity($quantity, $price, $idSell);
                $this->view->message = "Modification(s) sauvegard�(s)";
            }
        }

        $commande = new Application_Model_Command();
        $commandeline = new Application_Model_Commandline();
        //Les produits de l'utilisateur achetés par d'autre clients.
        //$this->view->commande = $commande->getIdCommand($ns->data['id_user']);     
        // Achat du client
        $tabCommand = $commande->getIdCommand($ns->data['id_user']);
        $this->view->command = $tabCommand;
        $this->view->commandeLine = $commandeline->getCommmandLine($tabCommand);
        //var_dump($this->view->commandeLine); die;

        $sell = new Application_Model_Sell();
        $images = new Application_Model_Image();
        $categorys = new Application_Model_Category();
        $user = new Application_Model_User();
        //Historique des vente effectué par un vendeur : Visualisation des commandes des clients
        $idProduct = $sell->getIdProductBySeller($ns->data['id_user']);
        $Commande = $commandeline->getCommandLineByArrayIdProduct($idProduct, true);
        $detailCommand = $commandeline->getCommandLineByArrayIdProduct($idProduct);
        $this->view->commandVente = $Commande;
        $complementCommand = $commandeline->getCommandLineByArrayIdProduct($idProduct);
        $this->view->complementCommande = $complementCommand;
        $this->view->detailCommand = $detailCommand;
      
        $infoUser = $user->getUserByCommandLine($Commande);
        $this->view->infoBuyer = $infoUser;

        $this->view->category = $categorys->getRubrique();

        $this->view->souscategory = $categorys->getSousRubrique();

        $table = $sell->getUserSell($ns->data['id_user']);


        foreach ($table as &$value) {
            $value['image'] = base64_encode($value['image']);
            $value['name_image'] = pathinfo($table['name_image'], PATHINFO_EXTENSION);
        }

        $this->view->articles = $table;
    }

}

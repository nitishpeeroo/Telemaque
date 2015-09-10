<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        zend_session::start();
        $ns = new Zend_Session_Namespace('user');
        $general = new Application_Model_General();
        $slider = $general->getSlider(); 
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
                    $this->view->message = "Vous êtes connecté";
                break;
                case 'enregistrer':
                    $this->view->message = "Vous êtes inscrit";
                break;
                case 'erreur':
                    $this->view->message = "Une erreur c'est produite lors de votre inscription.<br/>Veuillez contactez un administrateur";
                break;
            }
        }
        
    }

    public function indexAction() {
        $this->view->headTitle('Accueil');
        $sell = new Application_Model_Sell();
        $product = $sell->getNewsArticles(10);
        $product = $sell->convertImageSousRubrique($product);
        $this->view->product = $product;
        $general = new Application_Model_General();
        $data_webite = $general->getGeneral();
        $data = $data_webite[0];
        $this->view->catchphrase = $data['catchphrase'];
        $general = new Application_Model_General();
        $slider = $general->getSlider();
        $this->view->slider = $slider;
    }
    
    public function contactAction(){
        $general = new Application_Model_General();
        $data_webite = $general->getGeneral();
        $data = $data_webite[0];
        $this->view->website_address = $data['website_address'];
        $this->view->website_phone = $data['website_phone'];
        $this->view->website_email = $data['website_email'];
        $this->view->website_ville = $data['website_ville'];
        $this->view->website_code_postal = $data['website_code_postal'];
        $this->view->website_pays = $data['website_pays'];
        if($this->_request->isPost())
        {
            $contact = new Application_Model_Contact();
            $nom        = $_POST['nom'];
            $prenom     = $_POST['prenom'];
            $object     = $_POST['object'];
            $mailUser   = $_POST['mail'];
            $message    = $_POST['message'];
            $contact->sendMail($nom, $prenom , $object, $mailUser, $message);          	
            mail($mailUser , $object , $message);
            $this->view->mail = "Votre message à bien été envoyé.";
        }
    }
    
    public function errorAction(){
        if ($this->_getParam('type') == 'page') {
            $this->view->error = "Vous devez être connecté pour accéder à cette page.";
        }
    }
    
    public function accesAction(){
       
    }
    

    
        

}

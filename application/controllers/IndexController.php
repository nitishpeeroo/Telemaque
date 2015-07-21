<?php

class IndexController extends Zend_Controller_Action {

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
        $this->view->headTitle('Accueil');
        $sell = new Application_Model_Sell();
        $product = $sell->getNewsArticles(10);
        $product = $sell->convertImageSousRubrique($product);
        $this->view->product = $product;
        $general = new Application_Model_General();
        $data_webite = $general->getGeneral();
        $data = $data_webite[0];
        $this->view->catchphrase = $data['catchphrase'];
     
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
    }
    

    
        

}

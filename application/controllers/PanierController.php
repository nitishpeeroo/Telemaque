<?php

class PanierController extends Zend_Controller_Action {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->view->headTitle('Panier');
    }
    
    public function paiementAction(){
         $this->view->headTitle('Paiement');
    }
    
    public function factureAction(){
        $this->view->headTitle('Facture');
    }

}

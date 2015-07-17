<?php

class PanierController extends Zend_Controller_Action {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->view->headTitle('Panier');
        if ($this->_request->isPost()) {
            $id= $_POST['id_product'];
            $qte = $_POST['qte_product'];
            $price = $_POST['price_product'];
            setcookie("cookie[id]", $id);
            setcookie("cookie[qte]", $qte);
            setcookie("cookie[price]", $price);

          
        }
    }

    public function paiementAction() {
        $this->view->headTitle('Paiement');
    }

    public function factureAction() {
        $this->view->headTitle('Facture');
        if (isset($_POST['id_product'])) {
            $this->view->id = $_POST['id_product'];
        }
    }

}

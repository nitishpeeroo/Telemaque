<?php

class PanierController extends Zend_Controller_Action {

    public function init() {
        parent::init();
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('index', 'html')
                ->addActionContext('addPanier', array('html', 'json'))
                ->initContext();
        if ($this->_request->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
        zend_session::start();
        $panier = new Zend_Session_Namespace('panier');
          $ns = new Zend_Session_Namespace('user');

        if (!empty($ns->data)) {
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->lvl = $ns->data['id_rank'];
        }
    }

    public function indexAction() {
        $sell = new Application_Model_Sell();
        $this->view->headTitle('Panier');
        if (isset($_SESSION['panier'])) {
            $session = $_SESSION['panier'];

            $tab = array();
            foreach ($session as $key => $val) {

                $tab[] = $key;
            }
            $panier = $sell->getArticle(null, $tab);
            $panier = $sell->convertImageSousRubrique($panier);
            $this->view->panier = $panier;
            $this->view->session = $session;
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

    public function addpanierAction() {
        $id = $this->_request->getPost('id_product');
        $quantite = $this->_request->getPost('quantite');
        $article = new Application_Model_Sell();
        $produit = $article->getArticle($id);
        if ($produit[0] != null) {
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = array();
            }
            if (isset($_SESSION['panier'][$produit[0]['id_sell']])) {
                $_SESSION['panier'][$produit[0]['id_sell']] += $quantite;
            } else {
                $_SESSION['panier'][$produit[0]['id_sell']] = 0 + $quantite;
            }
        }
        $panier = $_SESSION['panier'];
        $this->_helper->json($panier);
    }

}

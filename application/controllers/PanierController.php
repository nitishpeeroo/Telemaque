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
        if (isset($_SESSION['panier'])) {

            $session = $_SESSION['panier'];
            if (!empty($session)) {

                $type = $this->_getParam('type');
                if ($type != null) {
                    $id = $this->_getParam('id');
                    if ($id != null) {
                        $this->deletepanier($id);
                    }
                }
                $session = $_SESSION['panier'];
                if (!empty($session)) {
                    $tab = array();
                    foreach ($session as $key => $val) {

                        $tab[] = $key;
                    }
                    $panier = $sell->getArticle(null, $tab);
                    $panier = $sell->convertImageSousRubrique($panier);
                    $this->view->panier = $panier;
                    $this->view->session = $session;
                } else {
                    $this->view->panier = null;
                    $this->view->session = null;
                }
            } else {
                $this->view->panier = null;
                $this->view->session = null;
            }
        } else {
            $this->view->panier = null;
            $this->view->session = null;
        }
    }

    public function paiementAction() {
        $this->view->headTitle('Paiement');
    }

    public function factureAction() {
        $this->view->headTitle('Facture');
        if(isset($_SESSION['panier']) && isset($_SESSION['user'])){
          if($_SESSION['user']['data']['id_rank'] =="2"){
              foreach ($_SESSION['panier'] as $id => $qte){
                 
              }
          }
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

    public function deletepanier($id) {
        unset($_SESSION['panier'][$id]);
    }

    public function ajoutquantiteAction() {
        $id = $this->_request->getPost('id_product');
        if (isset($_SESSION['panier'])) {
            $panier = array();
            $panier[$id]["quantite"] = $_SESSION['panier'] [$id];
            $panier[$id]['prix'] = 0;
            if (!empty($panier)) {
                $sell = new Application_Model_Sell();
                $prix = $sell->getArticle($id, array());
                $_SESSION['panier'][$id] ++;
                $panier[$id]["quantite"] ++;
                $panier[$id]['prix'] = $prix[0]['price'];
                $panier[$id]['prixTTC'] = $prix[0]['price'] * $_SESSION['panier'][$id];
                $panier[$id]['total'] = $this->_request->getPost('total') + $prix[0]['price'];
                $this->_helper->json($panier);
            }
        }
    }

    public function retirequantiteAction() {
        $id = $this->_request->getPost('id_product');
        if (isset($_SESSION['panier'])) {
            $panier = array();
            $panier[$id]["quantite"] = $_SESSION['panier'] [$id];
            $panier[$id]['prix'] = 0;
            if (!empty($panier)) {
                $sell = new Application_Model_Sell();
                if ($panier[$id]["quantite"] === 1) {
                    $this->deletepanier($id);
                    $prix = $sell->getArticle($id, array());
                    $panier[$id]["quantite"] = 0;
                    $panier[$id]['total'] = $this->_request->getPost('total') - $prix[0]['price'];
                    $this->_helper->json($panier);
                } else {

                    $prix = $sell->getArticle($id, array());
                    $_SESSION['panier'][$id] --;
                    $panier[$id]["quantite"] --;
                    $panier[$id]['prix'] = $prix[0]['price'];
                    $panier[$id]['prixTTC'] = $prix[0]['price'] * $_SESSION['panier'][$id];
                    $panier[$id]['total'] = $this->_request->getPost('total') - $prix[0]['price'];
                    $this->_helper->json($panier);
                }
            }
        }
    }

}

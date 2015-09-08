<?php

class CategoryController extends Zend_Controller_Action {

    public function init() {

        parent::init();
        $ns = new Zend_Session_Namespace('user');
        $general = new Application_Model_General();
        if (!empty($ns->data)) {
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->lvl = $ns->data['id_rank'];
        }
        $this->category = new Application_Model_Category();
    }

    public function indexAction() {
        $id_category = $this->_getParam('type');
        $categorie = new Application_Model_Category();
        $produit = $categorie->getArticleByCategory($id_category);
        $produit = $categorie->convertImageSousRubrique($produit);
        $this->view->produit = $produit;
    }

}

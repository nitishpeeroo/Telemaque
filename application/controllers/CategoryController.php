<?php

class CategoryController extends Zend_Controller_Action {

    public function init() {

        parent::init();

        $this->article = new Application_Model_Article();
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

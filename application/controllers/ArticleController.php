<?php

class ArticleController extends Zend_Controller_Action {
    
    public function init() {
        
        parent::init();
        
        $this->article  = new Application_Model_Article();
        $this->category = new Application_Model_Category();
    }
    
    public function searchAction(){
         if (isset($_POST['search'])) {
            $label = $_POST['search'];
            $article = new Application_Model_Article();
            $produit = $article->searchArticle($label);
            $this->view->produit = $produit;
            
        }
    }
    
    public function productAction(){
        
    }
}
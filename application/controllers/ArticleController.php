<?php

class ArticleController extends Zend_Controller_Action {

    public function init() {

        parent::init();
        $ns = new Zend_Session_Namespace('user');

        if (!empty($ns->data)) {
            $this->view->firstname = $ns->data['firstname_user'];
            $this->view->lastname = $ns->data['lastname_user'];
            $this->view->lvl = $ns->data['id_rank'];
        }
        $this->article = new Application_Model_Article();
        $this->category = new Application_Model_Category();
    }

    public function searchAction() {

        if (isset($_GET['search'])) {
            $label = $_GET['search'];
            $article = new Application_Model_Sell();
            $produit = $article->searchArticle($label);
            $total = $article->countArticle($produit);
            $perPage = 12;
            $nbPage = ceil($total / $perPage);
            $currentPage = $this->_getParam('page');
            if ($currentPage > 0 && $currentPage <= $nbPage) {
                $cPage = $currentPage;
            } else {
                $cPage = 1;
            }
            $startData = ($cPage - 1) * $perPage;
            $this->view->produit = array_slice($produit, $startData, $perPage);
            foreach ($this->view->produit as &$p) {
                $p['img64'] = base64_encode($p['image']);
                $p['type'] = pathinfo($p['name_image'], PATHINFO_EXTENSION);
            }
            //Pagination
            $pagination = "";
            $pagination.="<li class='disabled'><a href='#'>«</a></li>";
            for ($i = 1; $i <= $nbPage; $i++) {
                if ($i == $cPage) {
                    $pagination.="<li class='active'><a href='#'>" . $i . "<span class='sr-only'>(current)</span></a></li>";
                } else {
                    $pagination.= "<li><a href='/article/search/page/" . $i . "?search=" . $label . "'>$i</a></li>";
                }
            }
            $pagination.="<li><a href='#'>»</a></li>";

            $this->view->pagination = $pagination;
        }
    }

    public function productAction() {
        $fiche = $this->_getParam('fiche');
        $article = new Application_Model_Sell();
        $produit = $article->getArticle($fiche);
        $this->view->produit = $produit;
        foreach ($this->view->produit as &$p) {
            $p['img64'] = base64_encode($p['image']);
            $p['type'] = pathinfo($p['name_image'], PATHINFO_EXTENSION);
        }

    }
    
    public function modifyAction() {
        $fiche = $this->_getParam('fiche');
        $article = new Application_Model_Sell();
        
        $ns = new Zend_Session_Namespace('user');
        $sell = new Application_Model_Sell();
        $images = new Application_Model_Image();
        $categorys = new Application_Model_Category();
        
        if($this->_request->isPost())
        {        
            $title = $_POST['title'];
            
            // image data 
            $image = @file_get_contents($_FILES['image']['tmp_name']);
            $nameImage = $_FILES['image']['name'];
            
            // sell data
            $quantity = $_POST['quantity'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $descritptionCourt = $_POST['descritptionCourt'];
            $descritption = $_POST['descritption'];
            $idUser = $ns->data['id_user'];
            
            
            // recuperation de idSell après insertion
            //$idSell = $sell->updateSell($title,$image,$quantity,$category,$price,$descritptionCourt,$descritption, $idUser);
            
 
            // insertion de l'image grace a l'idSell
            //$ResultImage = $images->updateImage($idSell, $image, $nameImage);
//            if($ResultImage == true)
//            {
//                $this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'article'), null, true));
//            }    
        }           
        $produit = $article->getArticle($fiche); 
        $this->view->produit = $produit;
        
        foreach ($this->view->produit as &$p) {
            $p['img64'] = base64_encode($p['image']);
            $p['type'] = pathinfo($p['name_image'], PATHINFO_EXTENSION);
        }
    }

}

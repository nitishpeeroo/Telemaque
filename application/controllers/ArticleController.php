<?php

class ArticleController extends Zend_Controller_Action {

    public function init() {

        parent::init();
        $ns = new Zend_Session_Namespace('user');
        $general = new Application_Model_General();
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
           Zend_Session:: namespaceUnset("user");
           Zend_Session::destroy(true);
           $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'acces'), null, true));
        }
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
        $ns = new Zend_Session_Namespace('user');
        if (empty($ns->data)) {
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'error','type'=> 'page'), null, true));
        }
        $fiche = $this->_getParam('fiche');
        $article = new Application_Model_Sell();
        
        $ns = new Zend_Session_Namespace('user');
        $sell = new Application_Model_Sell();
        $images = new Application_Model_Image();
        $categorys = new Application_Model_Category();
        $produit = $article->getArticle($fiche); 
        
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
            $descritptionCourt = $_POST['description_courte'];
            $descritption = $_POST['description'];
            $idUser = $ns->data['id_user'];

            // recuperation de idSell après modification
            $sell->updateSell($title,$image,$quantity,$price,$descritptionCourt,$descritption, $fiche);   

            // insertion de l'image grace a l'idSell
            $produit = $article->getArticle($fiche); 
            if($nameImage != '')
            {
                $ResultImage = $images->updateImage($produit[0]['id_image'], $image, $nameImage);
            }
            if($ResultImage == true)
            {
                $this->_redirect($this->view->url(array('controller' => 'article', 'action' => 'modify','fiche'=> $fiche), null, true));
            }    
        }           
        
        $this->view->produit = $produit;
        
        foreach ($this->view->produit as &$p) {
            $p['img64'] = base64_encode($p['image']);
            $p['type'] = pathinfo($p['name_image'], PATHINFO_EXTENSION);
        }
    }

}

<?php

class ArticleController extends Zend_Controller_Action {

    public function init() {

        parent::init();

        $this->article = new Application_Model_Article();
        $this->category = new Application_Model_Category();
    }

    public function searchAction() {

        if (isset($_GET['search'])) {
            $label = $_GET['search'];
            $article = new Application_Model_Article();
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
             $this->view->produit = array_slice($produit, $startData,  $perPage);
            foreach ( $this->view->produit as &$p){
                $p['img64']= base64_encode($p['image']);
                $p['type'] =pathinfo($p['name_image'], PATHINFO_EXTENSION);
            }
            //Pagination
            $pagination = "";
            $pagination.="<li class='disabled'><a href='#'>«</a></li>";
            for ($i = 1; $i <= $nbPage; $i++) {
                if ($i == $cPage) {
                      $pagination.="<li class='active'><a href='#'>".$i."<span class='sr-only'>(current)</span></a></li>";
                } else {
                    $pagination.= "<li><a href='/article/search/page/" . $i . "?search=" . $label . "'>$i</a></li>";
                }
            }
            $pagination.="<li><a href='#'>»</a></li>";

            $this->view->pagination = $pagination;
           
        }
    }

    public function productAction($id) {
        
    }

}

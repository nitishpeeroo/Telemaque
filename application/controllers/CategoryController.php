<?php

class CategoryController extends Zend_Controller_Action {
    
    public function init() {
        
        parent::init();
        
        $this->article  = new Application_Model_Article();
        $this->category = new Application_Model_Category();
    }
}
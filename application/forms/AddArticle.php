<?php

class Application_Form_AddArticle extends Zend_Form {

    public function init() {
        
        $this   ->addElement('text', 'label_article', array(
                    'required'      => true,
                    'placeholder'   => 'Label',
                    'class'         => 'form-control'
                ));
        
        $this   ->setAttrib('action', '');
        $this   ->setMethod('POST');
    }
}
<?php

class Application_Form_AddCategory extends Zend_Form {

    public function init() {
        
        $this   ->addElement('text', 'label_category', array(
                    'required'      => true,
                    'placeholder'   => 'Label',
                    'class'         => 'form-control'
                ));
        
        $this   ->setAttrib('action', '');
        $this   ->setMethod('POST');
    }
}
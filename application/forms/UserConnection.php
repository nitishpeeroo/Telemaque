<?php

class Application_Form_UserConnection extends Zend_Form {

    public function init() {
        
        $this   ->setAttrib('action', '');
        $this   ->setMethod('POST');
        
        $this   ->addElement('text', 'first_name', array(
                    'required'      => true,
                    'placeholder'   => 'Prénom',
                    'class'         => 'form-control input-sm',
                    'id'            => 'first_name'
                ));
        
        $this   ->addElement('text', 'last_name', array(
                    'required'      => true,
                    'placeholder'   => 'Nom',
                    'class'         => 'form-control input-sm',
                    'id'            => 'last_name'
                ));
        $this   ->addElement('text', 'email', array(
                    'required'      => true,
                    'placeholder'   => 'Email',
                    'class'         => 'form-control input-sm',
                    'id'            => 'email'
                ));
        
        $this   ->addElement('password', 'password', array(
                    'required'      => true,
                    'placeholder'   => 'Mot de passe',
                    'class'         => 'form-control input-sm',
                    'id'            => 'password'
                ));
        
        $this   ->addElement('text', 'login', array(
                    'required'      => true,
                    'placeholder'   => 'Identifiant',
                    'class'         => 'form-control input-sm',
                    'id'            => 'password'
                ));
        
        $this   ->addElement('text', 'phone', array(
                    'required'      => true,
                    'placeholder'   => 'téléphone',
                    'class'         => 'form-control input-sm',
                    'id'            => 'password_confirmation'
                ));
        
        $this   ->addElement('text', 'address', array(
                    'required'      => true,
                    'placeholder'   => 'Adresse',
                    'class'         => 'form-control input-sm',
                    'id'            => 'email'
                ));
        
        $this   ->addElement('submit', 'submit', array(
                    'required'      => true,
                    'placeholder'   => "S'enregistrer",
                    'class'         => 'btn btn-info btn-block'
                ));
        
    }
}

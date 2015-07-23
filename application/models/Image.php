<?php

class   Application_Model_Image extends Zend_Db_Table_Abstract {

    protected $_dbname = DB_NAME_TELEMAQUE;
    protected $_name = DB_TABLE_IMAGE;
    public $data = array();

    public function addImage($idSell, $image, $nameImage) {
            
        try {
            $data = array(
                'id_sell' => $idSell,
                'image' => $image,
                'name_image' => $nameImage,
            );
            
                 $this->insert($data);
            
        } catch (Exception $ex) {

            echo 'ERROR_INSERT_ADDIMAGE : ' . $ex->getMessage();
            return false;
        }

        return true;
    }
    
    public function updateImage($idImage, $image, $nameImage) {
        $data = array(
            'image' => $image,
            'name_image' => $nameImage,
        );
        $this->update($data,'id_image = ' . $idImage);
        
        return true;
    }
}
<?php

/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoTelemaque {
    
    private static $serveur         = 'mysql:host=localhost';
    private static $bdd             = 'dbname=telemaque';   		
    private static $user            = 'root';    		
    private static $mdp             = '';
    
    private static $monPdo;
    private static $monPdoTelemaque = null;
    
    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */				
    private function __construct() {
        
        PdoTelemaque::$monPdo = new PDO(PdoTelemaque::$serveur.';'.PdoTelemaque::$bdd, 
                                        PdoTelemaque::$user, 
                                        PdoTelemaque::$mdp); 
        
        PdoTelemaque::$monPdo->query("SET CHARACTER SET utf8");
    }
    public function _destruct() {
        
        PdoTelemaque::$monPdo = null;
    }
    
    /**
     * Fonction statique qui crée l'unique instance de la classe

     * Appel : $instancePdoTelemaque = PdoTelemaque::getPdoTelemaque();

     * @return l'unique objet de la classe PdoGsb
     */
    public  static function getPdoTelemaque(){
        
        if(PdoTelemaque::$monPdoTelemaque == null) {
            
            PdoTelemaque::$monPdoTelemaque = new PdoTelemaque();
        }
        
        return PdoTelemaque::$monPdoTelemaque;  
    }
    
    
    public function getArticles() {
        
        PdoTelemaque::$monPdo->prepare('SELECT * FROM article');
    }
    
    
    public function addArticle($label, $image_article) {
        try{
            $dt_creation = new DateTime;
            $dt_creation->format('Y-m-d H:i:s');

            $stmt = PdoTelemaque::$monPdo->prepare('INSERT INTO article (label, is_checked, dt_creation, dt_modification, image_article)'
            . 'VALUES (:label, :is_checked, NOW(), NOW(), :image_article)');

            $stmt->bindValue(':label', $label);
            $stmt->bindValue(':is_checked', 0);
            //$stmt->bindValue(':dt_creation', $dt_creation);
            //$stmt->bindValue(':dt_modification', $dt_creation);
            $stmt->bindValue(':image_article', $image_article);
            $stmt->execute();
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
}
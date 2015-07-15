<?php

/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application Telemaque
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoTelemaque qui contiendra l'unique instance de la classe
 
 * @package     default
 * @author      Rémi Kilhoffer
 * @version     1.0
 * @link        http://www.php.net/manual/fr/book.pdo.php
 */

class PdoTelemaque {
    
    private static $serveur         = 'mysql:host=localhost';
    private static $bdd             = 'dbname=telemaque';   		
    private static $user            = 'root';    		
    private static $mdp             = '';
    
    private static $monPdo;
    private static $monPdoTelemaque = null;
    

    
    private function __construct()                                  {
        
        PdoTelemaque::$monPdo = new PDO(PdoTelemaque::$serveur.';'.PdoTelemaque::$bdd, 
                                        PdoTelemaque::$user, 
                                        PdoTelemaque::$mdp); 
        
        PdoTelemaque::$monPdo->query("SET CHARACTER SET utf8");
    }
    
    public function _destruct()                                     {
        
        PdoTelemaque::$monPdo = null;
    }

    public  static function getPdoTelemaque()                       {
        
        if(PdoTelemaque::$monPdoTelemaque == null) {
            
            PdoTelemaque::$monPdoTelemaque = new PdoTelemaque();
        }
        
        return PdoTelemaque::$monPdoTelemaque;  
    }
    
    
    
    
    
    /**************************************************************** 
     ****************     GESTION DES ARTICLES     ******************
     ****************************************************************/
    
    //Retourne tous les articles
    public function getArticles()                                   {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM article');
        $stmt->execute();
    }
    
    //Retourne l'id d'un article (pour modifier cet article)        
    public function getArticleID($label)                            {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT id_article FROM article'
             . ' WHERE label_article = :label');
        
        $stmt->bindValue(':label', $label);
        $stmt->execute();
    }

    //Retourne l'article voulu pour la modification
    public function getArticle($id)                                 {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM article'
             . ' WHERE id = :id');
        
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }    
    
    //Retourne les articles de la catégorie souhaitée
    public function getArticlesByCategory($labelCategory)           {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM article a'
             . ' INNER JOIN article_category ac'
             . ' ON ac.id_article = a.id_article'
             . ' INNER JOIN category c'
             . ' ON c.id_category = ac.id_category'
             . ' WHERE c.label_category = :label');
        
        $stmt->bindValue(':label', $labelCategory);
        $stmt->execute();
    }

    //Ajoute un nouvel article
    public function addArticle($label, $img)                        {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'INSERT INTO article (label_article, image_article)'
              .' VALUES (:label, :image_article)');
        
        $stmt->bindValue(':label', $label);
        $stmt->bindValue(':image_article', $img);
        $stmt->execute();
    }
    
    //Modifie l'article selectionné
    public function updateArticle($id, $img, $label)                {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'UPDATE article'
             . ' SET image_article = :img,'
                 . ' label_article = :label,'
                 . ' dt_modification = NOW()'
             . ' WHERE id_article = :id');
        
        $stmt->bindValue(':img', $img);
        $stmt->bindValue(':label', $label);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    //Supprime l'article selectionné
    public function deleteArticle($id)                              {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'DELETE FROM article WHERE id_article = :id');
        
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    //Retourne l'article recherché
    public function searchArticle($label)                           {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM article'
             . ' WHERE label_article LIKE \'%:label%\'');
        
        $stmt->bindValue(':label', $label);
        $stmt->execute();
    }
    
    
    
    
    
    /**************************************************************** 
     ****************     GESTION DES CATÉGORIES     ****************
     ****************************************************************/
    
    //Retourne toutes les catégories d'article
    public function getCategories()                                 {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM category');
        
        $stmt->execute();
    }
    
    //Retourne la catégorie voulue pour la modification
    public function getCategory($id)                                {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM category'
             . ' WHERE id_category = :id');
        
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    //Ajoute une nouvelle catégorie d'articles
    public function addCategory($label)                             {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'INSERT INTO category (label_category)'
             . ' VALUES(:label)');
        
        $stmt->bindValue(':label', $label);
        $stmt->execute();
    }
    
    //Modifie la catégorie selectionnée
    public function updateCategory($id, $label)                     {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'UPDATE category'
             . ' SET label_category = :label'
             . ' WHERE id_category = :id');
        
        $stmt->bindValue(':label', $label);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    //Supprime la catégorie selectionnée
    public function deleteCategory($id)                             {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'DELETE FROM category WHERE id_category = :id');
        
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    
    
    
    
   /**************************************************************** 
    ***************     GESTION DES UTILISATEURS    ****************
    ****************************************************************/
    
    //Retourne l'utilisateur si il existe
    public function getUser($login, $password)                    {
          
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM user'
             . ' WHERE login_user = :login'
             . ' AND password_user = :password');
        
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);        
        return $result;
    }
    
    //Retourne les informations d'un utilisateur choisi
    public function getInfosUser($id)                             {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT * FROM user'
             . ' WHERE id_user = :id');
        
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    //Ajoute un nouvel utilisateur (après la création de compte)  
    public function addUser($firstname, $lastname, $address, $login, 
                            $rank, $password, $mail, $phone)      {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'INSERT INTO user (address_user, firstname_user, lastname_user, level_user,'
                               . ' login_user, mail_user, password_user, phone_user)'
             . ' VALUES(:address, :firstname, :lastname, :level, :login, :mail, :password, :phone)');
        
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':firstname', $firstname);
        $stmt->bindValue(':lastname', $lastname);
        $stmt->bindValue(':level', $rank);
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':mail', $mail);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':phone', $phone);
        $stmt->execute();
    }
    
    //Modifie les informations d'un utilisateur choisi
    public function updateUser($id, $firstname, $lastname, 
                               $address, $mail, $phone, $level)   {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'UPDATE user'
             . ' SET firstname_user = :firstname,'
                 . ' lastname_user = :lastname,'
                 . ' address_user = :address,'
                 . ' mail_user = :mail,'
                 . ' phone_user = :phone,'
                 . ' level_user = :level'
             . ' WHERE id_user = :id');
        
        $stmt->bindValue(':firstname', $firstname);
        $stmt->bindValue(':lastname', $lastname);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':mail', $mail);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':level', $level);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    //Efface un utilisateur
    public function deleteUser($id)                               {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'DELETE FROM user WHERE id_user = :id');
        
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
    
    
    
    
    
    /**************************************************************** 
     ***************     GESTION DES VENTES    **********************
     ****************************************************************/
    
    //Retourne les produits en vente et activés ou non par l'admin (1 ou 0)
    public function getSells($available)                          {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'SELECT s.description, s.price, s.quantity,'
                    . ' a.image_article, a.label_article,'
                    . ' u.firstname_user, u.lastname_user, u.mail_user'
             . ' FROM sell s'
             . ' INNER JOIN article a'
             . ' ON a.id_article = s.id_article'
             . ' INNER JOIN user u'
             . ' ON u.id_user = s.id_user'
             . ' WHERE s.available = :available'
             . ' ORDER BY a.label_article');
        
        $stmt->bindValue(':available', $available);
        $stmt->execute();
    }
    
    //Active ou désactive les ventes (pour l'admin)
    public function setSells($available, $id)                     {
        
        $stmt = PdoTelemaque::$monPdo->prepare(
                'UPDATE sell '
             . ' SET available = :available'
             . ' WHERE id_sell = :id');
        
        $stmt->bindValue(':available', $available);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
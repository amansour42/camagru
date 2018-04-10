<?php
require 'database.php';

function setup($dbh,$dbname)
{
      //creation de la base camagru
	$sql = "DROP DATABASE IF EXISTS ".$dbname;
	$result = $dbh->exec($sql);
	$sql = "CREATE DATABASE IF NOT EXISTS ".$dbname;
	$result = $dbh->exec($sql);
      //utiiser la base de donnees camagru
	$sql = "USE ".$dbname;
	$result = $dbh->exec($sql);
      //creation de la table membres
	$sql = "CREATE TABLE membres (
      id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
      pseudo VARCHAR(255) NOT NULL,
      mdp VARCHAR(255) NOT NULL,
      mail VARCHAR(255) NOT NULL,
      confirmekey VARCHAR(255),
      confirme INT(1) default 0
      )";
	$result = $dbh->exec($sql);
      //creation de la base images
	$sql = "CREATE TABLE images (
      img_id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      img_path VARCHAR(255) NOT NULL,
      id int NOT NULL,
      img_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
      CONSTRAINT fk_img_user FOREIGN KEY(id) REFERENCES membres(id)
		)";
	$result = $dbh->exec($sql);
      
      //creation de la table recuperation
	$sql = "CREATE TABLE recuperation (
      recup_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      mail VARCHAR(255) NOT NULL,
      code VARCHAR (8) NOT NULL
      )";
	$result = $dbh->exec($sql);
}

try{
      $db = new PDO(  $DB_DSN,
                $DB_USER,
                $DB_PASSWORD
            );
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      setup($db, $DB_NAME);
      echo 'setup completed'.PHP_EOL;
}
catch(PDOException $e)
{
      echo $e->getMessage();
      die();
}
?>
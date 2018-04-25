<?php
include 'database.php';
try{
    $param = $DB_DSN."dbname=".$DB_NAME;
    $bdd = new PDO(  $param,
              $DB_USER,
              $DB_PASSWORD
          );
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo $e->getMessage();
    die();
}
?>
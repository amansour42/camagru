<?php
include '../config/database.php';
try{
    $bdd = new PDO(  $DB_DSN,
              $DB_USER,
              $DB_PASSWORD
          );
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    setup($bdd,$DB_NAME);
    echo 'setup completed'.PHP_EOL;
}
catch(PDOException $e)
{
    echo $e->getMessage();
    die();
}
?>
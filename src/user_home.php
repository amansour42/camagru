<?php
session_start();
include "../config/conection.php";

if (isset($_GET['id']) AND $_GET['id'] > 0)
{
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
    $_SESSION['user'] = $userinfo['pseudo'];
    $_SESSION['id'] = $getid;
    //traitement image
    if (isset($_FILES['image']) AND !empty($_FILES['image']['name']))
    {
        $taillemax = 2097152;
        $extensionvalide= array('jpg', 'jpeg', 'gif', 'png');
        if ($_FILES['image']['size'] <= $taillemax)
        {
            $extensionupload = strtolower(substr(strrchr($_FILES['image']['name']),'.' ), 1);
            if (in_array($extensionupload, $extensionvalide))
            {
                $chemin = "../img/photos/".$_SESSION['id'].".".$extensionupload;
                $resultat = move_upload_file($_FILS['image']['tmp_name'], $chemin);
                if ($resultat)
                {
                    $insert = $bdd->prepare("INSERT INTO images(img_path, user_id) VALUES (? , ?)");
                    $insert->execute(array($chemin, $_SESSION['id']));
                }
                else
                {
                    $error = "Erreur durant l'importation de votre image";
                }

            }
            else
                {
                    $error = "Votre Photo Doit Etre D'extension png, jpeg, jpg, gif";
                }
        }
        else
            $error = "Votre Photo Ne Doit Pas Depasser 2Mo";
    }

?>

<html>
<head>
    <meta charset="utf-8" />
    <title>Page De Profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../style/home.css" />
    <link rel="stylesheet" href="../style/user.css" />
</head>
<body>
<div class="first">
        <a class="right" href="edition.php"> Editer Mon Profil </a>
        <img class="logo" src="../img/logo42.png" alt="logo42" title="42">
        <a class= "left" href="deconection.php">Se Deconnecter </a>
	</div>
    <?php
        if (isset($_FILES['image']) AND !empty($_FILES['image']))
        {?>
        <img src=<?php echo $chemin;?> width="100px" height = "100px">    
        <?php } ?>
    
    <label>Prendre une Photo</label>
    <form method"post" action="" enctype="multipart/form-data">
        <input type="file" name="image" />
        <input type="submit" name= "submit" value="envoyer" />
    </form>
</body>
</html>
<?php
}
?>
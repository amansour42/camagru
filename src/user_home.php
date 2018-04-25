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
            echo "YES";
            $extensionupload = strtolower(substr(strrchr($_FILES['image']['name'],'.' ), 1));
            if (in_array($extensionupload, $extensionvalide))
            {
                $chemin = "../img/photos/".$_SESSION['id'].".".$extensionupload;
                $resultat = move_uploaded_file($_FILES['image']['tmp_name'], $chemin);
                if ($resultat)
                {
                    $insert = $bdd->prepare("INSERT INTO images(img_path, id) VALUES (? , ?)");
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
    <link rel="stylesheet" href="../style/user.css" />
    <link rel="stylesheet" href="../style/commun.css" />
    <link rel="icon" type="image/png" href="img/logo42.png"/>
</head>
<body>
<div class="first">
        <a class="right" href="edition.php"> Editer Mon Profil </a>
        <img class="logo" src="../img/logo42.png" alt="logo42" title="42">
        <a class= "left" href="deconection.php">Se Deconnecter </a>
</div>
 <?php echo '<h1>'.$_SESSION['login'].'@camagru</h1>'; ?>
<table>
<tr>
    <td>
        <h1>1</h1>
        <video id="video" autoplay></video>
        <br />
        <button id="startbutton">Prendre une photo</button>
        <br/>
        <form method="post" action="" enctype="multipart/form-data">
        <input type="file" name="image" />
        <input type="submit" name= "submit" value="envoyer" />
        </form>
    </td>
    <td>
        <h1>2</h1>
        <img src="../img/icons/1.jpg" width=200px height=200px id="icon">
        <br />
        <form method="get" action="">
        <select id="op" name="op" onchange="change()">
        <option  value='1'>1</option>
        <option  value='2'>2</option>
        <option  value='3'>3</option>
        <option  value='4'>4</option>
        <option  value='5'>5</option>
        <option  value='6'>6</option>
        <option  value='7'>7</option>
        <option  value='8'>8</option>
        <option  value='9'>9</option>
        <option  value='10'>10</option>
        <option  value='11'>11</option>
        <option  value='12'>12</option>
        </select>
        </form>
        </td>
        <script src="../javascript/app.js"></script>
        <td>
        <canvas id="canvas"> </canvas>
        <script type="text/javascript" src="../javascript/webcam.js"></script>
        <?php
            if (isset($_FILES['image']) AND !empty($_FILES['image']) AND $_FILES['image']['size'] <= $taillemax)
            {?>
            <img src=<?php echo $chemin;?> width="300px" height = "200px">    
            <?php } ?>
    </td>
    </tr>
    </table>
    <?php
        if (isset($error))
		    echo '<p style="color:red;dislay:block" align="center">'.$error.'</p>';
    ?>
</body>
</html>
<?php
}
?>
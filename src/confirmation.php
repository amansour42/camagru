<?php
include "../config/conection.php";

if (isset($_GET['pseudo'], $_GET['key']) AND !empty($_GET['pseudo']) AND !empty($_GET['key']))
{
    $pseudo = htmlspecialchars(urldecode($_GET['pseudo']));
    $key = intval($_GET['key']);
    $requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
    $requser->execute(array($pseudo));
    $userexist = $requser->rowCount();

    if ($userexist == 1)
    {
        $user = $requser->fetch();
        if ($user['confirme'] == 0)
        {
            $updateuser = $bdd->prepare("UPDATE membres SET confirme = 1 WHERE pseudo = ? AND confirmekey = ?");
            $updateuser->execute(array($pseudo, $key));
            echo "Votre compte a ete bien confirme";
        }
        else
            echo "Votre compte existe deja !";
    }
    else
    {
        echo "L'utilisateur n'existe pas !";
    }
}
?>
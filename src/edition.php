<?php
session_start();
include "../config/conection.php";
if (isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if (isset($_POST['pseudo']) AND !empty($_POST['pseudo']) AND $_POST['pseudo'] != $user['pseudo'])
    {
        $new = htmlspecialchars($_POST['pseudo']);
        $requser = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
        $requser->execute(array($new, $_SESSION['id']));
        header("Location: user_home.php?id=".$_SESSION['id']);
    }
    if (isset($_POST['mail']) AND !empty($_POST['mail']) AND $_POST['mail'] != $user['mail'])
    {
        $new = htmlspecialchars($_POST['mail']);
        $requser = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
        $requser->execute(array($new, $_SESSION['id']));
        header("Location: user_home.php?id=".$_SESSION['id']);
    }
    if (isset($_POST['mdp']) AND !empty($_POST['mdp']))
    {
        $new = sha1($_POST['mdp']);
        $requser = $bdd->prepare("UPDATE membres SET mdp = ? WHERE id = ?");
        $requser->execute(array($new, $_SESSION['id']));
        header("Location: user_home.php?id=".$_SESSION['id']);
    }
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edition Compte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h2>Edition De Mon Profil</h2>
    <div align="center">
        <form method="post" action="">
        <label for="login"> Pseudo : </label><input id = "login" type="text" name="pseudo" value="<?php echo $user['pseudo']?>">
				<br />
				<label for="mail"> Mail : </label><input id="mail" type="email" name="newmail" value="<?php echo $user['mail']?>">
				<br />
                <br />
                <label for="passwd"> Mot De passe : </label><input id="passwd" type="password" name="newmdp">
				<br />
				<br />
				<input class="ok" type="submit" name="submit" value="Mettre a jour mon profil">
        </form>
    </div>
</body>
</html>
<?php
}
?>
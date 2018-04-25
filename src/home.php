<?php
session_start();
include "../config/conection.php";
if (isset($_POST['submit']))
{
	$pseudo = htmlspecialchars($_POST['pseudo']);
	$mdp = sha1($_POST['passwd']);
	if (!empty($pseudo) AND !empty($mdp))
	{
		$requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ? AND mdp = ?");
		$requser->execute(array($pseudo, $mdp));
		$userexist = $requser->rowCount();
		if ($userexist == 1)
		{
			$userinfo = $requser->fetch();
			if ($userinfo['confirme'] == 1)
			{
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['user'] = $userinfo['pseudo'];
				$_SESSION['mail'] = $userinfo['mail'];
				header("Location: user_home.php?id=".$_SESSION['id']);
			}
			else
				$error = "Vous devez confirmez votre compte !".$userinfo['confirme'];
		}
		else
			$error = "Mauvais Mail ou Mot De passe !";

	}
	else
		$error = "Tous les champs doivent etre remplis !";
}

?>
<html>
	<head>
		<TITLE>Acceuil</TITLE>
		<meta charset="UTF-8">
		<meta name="keywords" content="HTML,CSS,PHP,JavaScript,MySql">
		<meta name="author" content="Amina Mansour">
		<link rel="stylesheet" href="../style/home.css" />
		<meta name="viewport" content="width=device-width initial-scale=1.0" />
	</head>
	<body>
		<div class="first">
			<img class="logo" src="../img/logo42.png" alt="logo42" title="42">
		</div>
		<div class="second">
			<h1>Bienvenue A Camagru</h1>
			
			<form method="POST" action="">
				<img class="login" src="../img/bas.png" alt="login" title="login">
				<br /><br />
				<label for="login"> Pseudo : </label><input id = "login" type="text" name="pseudo">
				<br />
				<label for="passwd"> Mot De passe : </label><input id="passwd" type="password" name="passwd">
				<br />
				<br />
				<input class="ok" type="submit" name="submit" value="OK">
				<a href="./forget.php" alt="forget" title="Mot De Passe Oublié ?" align="center"><h3>Mot De Passe Oublié ?</h3></a>
				<a href="register.php" alt="Inscription" title="Inscription"><h3>S'inscrire</h3></a>
			</form>
			<?php
				if (isset($error))
					echo '<p style="color:red;dislay:block" align="center">'.$error.'</p>';
			?>
		</div>
	</body>
</html>
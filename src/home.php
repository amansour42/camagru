<?php
session_start();
include "../config/connection.php";
if (isset($_POST['submit']))
{
	$pseudo = htmlspecialchars($_POST['pseudo']);
	$mdp = sha1($_POST['passwd']);
	if (!empty($pseudo) AND !empty($mdp))
	{
		$requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ? AND mdp = ?");
		$requser = $bdd->execute(array($pseudo, $mdp));
		$userexist = $requser->rowCount();
		if ($userexist == 1)
		{
			$userinfo = $requser->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['user'] = $userinfo['pseudo'];
			$_SESSION['mail'] = $userinfo['mail'];
			header("Location: user_home.php?id=".$_SESSION['id']);

		}
		else
			$error = "Mauvais Mail ou Mot De passe !";

	}
	else
		$error = "Tous les camps doivent etre remplis !";
}

?>
<html>
	<head>
		<TITLE>Acceuil</TITLE>
		<meta charset="UTF-8">
		<meta name="keywords" content="HTML,CSS,PHP,JavaScript,MySql">
		<meta name="author" content="Amina Mansour">
		<link rel="stylesheet" href="../style/home.css" />
	</head>
	<body>
		<div class="first">
			<img src="../img/logo42.jpg" alt="logo42" title="42">
		</div>
		<h1>Bienvenue A Camagru</h1>
		<div class="second">
			<form method="POST" action="">
				<label for="login"> Pseudo : </label><input id = "login" type="text" name="pseudo">
				<br />
				<label for="passwd"> Mot De passe : </label><input id="passwd" type="password" name="passwd">
				<br />
				<br />
				<input class="ok" type="submit" name="submit" value="OK">
				<a href="./forget.php" alt="forget" title="forget your password ?"><h3>forget your password ?</h3></a>
			</form>
			<?php
				if (isset($error))
					echo $error;
			?>
		</div>
		<div>
			<a href="./register.html" alt="Inscription" title="Inscription"><H1>Register</H1></a>
		</div>
	</body>
</html>
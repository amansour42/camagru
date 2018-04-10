<?php
session_start();
include "mail.php";
include "../config/conection.php";
if (isset($_GET['section'])){
	$section = htmlspecialchars($_GET['section']);
}
else
	$section = "";

if (isset($_POST['submit'], $_POST['mail']))
{
	if (!empty($_POST['mail']))
	{
		$mail = htmlspecialchars($_POST['mail']);
		if (filter_var($mail, FILTER_VALIDATE_EMAIL))
		{
			$mailexist = $bdd->prepare('SELECT id,pseudo FROM membres WHERE mail = ?');
			$mailexist->execute(array($mail));
			$mailexist_count = $mailexist->rowCount();
			if ($mailexist_count == 1)
			{
				$pseudo = $mailexist->fetch();
				$pseudo = $pseudo['pseudo'];
				$_SESSION['mail'] = $mail;
				$code = "";
				for($i = 0; $i < 8 ; $i++)
				{
					$code .= mt_rand(0, 9);

				}
				$message='<html>
				<head>
				<title>Votre Nouveau De Passe Pour Camagru :)</title>
				</head>
				<body>
				<div align="center">
				<p> Votre nouveau mot de passe : <br/>'.$code.'</p>
				</div>
				</body>
				</html>';
				mail($mail, "Nouveau Mot De Passe", $message, $header);
				$mail_recup_exist = $bdd->prepare('SELECT * FROM recuperation WHERE mail = ?');
				$mail_recup_exist->execute(array($mail));
				$mail_recup_exist = $mail_recup_exist->rowCount();
				if ($mail_recup_exist == 1)
				{
					$insert = $bdd->prepare('UPDATE recuperation SET code = ? WHERE mail = ?');
					$insert->execute(array($code, $mail));
				}
				else
				{
					$insert = $bdd->prepare('INSERT INTO recuperation(mail, code) VALUES (?, ?)');
					$insert->execute(array($mail, $code));

				}
				header('Location:forget.php?section=code');
			}
			else
				$error = "Mail n'existe pas";
		}
		else
			$error = "Mail invalide";
	}
	else
	{
		$error = "Veuillez entrer votre mail";
	}
}
	if (isset($_POST['verif_submit']) AND isset($_POST['verif_code']))
	{
		if (!empty($_POST['verif_code']))
		{
			$verif_code = htmlspecialchars($_POST['verif_code']);
			$verif_req = $bdd->prepare('SELECT * FROM recuperation where mail = ? AND code = ?');
			$verif_req->execute(array($_SESSION['mail'], $verif_code));
			$req = $verif_req->rowCount();
			if ($req == 1)
			{
				$del_req = $bdd->prepare('DELETE FROM recuperation WHERE mail = ?');
				$del_req->execute(array($_SESSION['mail']));
				header('location:forget.php?section=changemdp');
			}
			else
				$error = "Mot De Passe de Confirmation est Invalide";
		}
		else
			$error = "Veuillez entrez votre Mot De Passe De confirmation !";
	}
	if (isset($_POST['change_submit']))
	{
		if (isset($_POST['conf']) && isset($_POST['changemdp']))
		{
			$mdp = htmlspecialchars($_POST['changemdp']);
			$mdpc = htmlspecialchars($_POST['conf']);
			if (!empty($mdp) AND !empty($mdpc)){
				if ($mdp == $mdpc)
				{
					$mdp = sha1($mdp);
					$ins_mdp = $bdd->prepare('UPDATE membres SET mdp = ? WHERE mail = ?');
					$ins_mdp->execute(array($mdp, $_SESSION['mail']));
					header('location: home.php');
				}
				else
					$error = "Vos Mots de passes ne correspondent pas";
			}
			else
				$error = "Veuillez remplir tous les champs";
		}
		else
			$error = "Veuillez remplir tous les champs";
	}
?>

<html>
	<head>
		<TITLE>Forget Password</TITLE>
		<link rel="stylesheet" href="../style/home.css" />
	</head>
	<body>
		<?php if($section == 'code') {?>
		<h1>Recuperation de mot de passe pour <?= $_SESSION['mail'] ?></h1>
		<br />
		<form method="POST" action="">
				<label for="code">Mot De Passe :</label><input id="code" type="text" name="verif_code" placeholder="Code de recuperation">
				<br />
				<br />
				<input type="submit" name="verif_submit" value="Envoyer">
			</form>
			<?php } else if ($section =='changemdp') {?>
				<h1>Nouveau Mot de passe pour  <?= $_SESSION['mail'] ?></h1>
		<br /><br />
		<form method="POST" action="">
				<label for="mdp">Mot De Passe :</label><input id="mdp" type="Password" name="changemdp" placeholder="Nouveau mot de passe">
				<br />
				<br />
				<label for="conf">Confirmation :</label><input id="conf" type="Password" name="conf" placeholder="Confirmation de mot de passe">
				<br /><br />
				<input type="submit" name="change_submit" value="Envoyer">
			</form>
		<?php } else { ?>
		<form method="POST" action="">
				<label for="mail">Mail :</label><input id="mail" type="email" name="mail" placeholder="Your Email">
				<br />
				<br />
				<input type="submit" name="submit" value="Envoyer">
			</form>
			<?php }?>
			<?php if (isset($error)) echo '<p style="color:red;text-align:center">'.$error.'</p>'; ?>
	</body>
</html>
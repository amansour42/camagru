<?php
session_start();
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
			$mailexist_count = $mailexist->rowcount();
			if ($mailexist_count == 1)
			{
				$pseudo = $mailexist->fetch();
				$pseudo = $pseudo['pseudo'];
				$_SEESION['mail'] = $mail;
				$code = "";
				for($i = 0; $i < 8 ; $i++)
				{
					$code .= mt_rabd(0, 9);

				}
				$mail_recup_exists = $bdd->prepare('SELECT INTO recuperation WHERE mail = ?');
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
			}
			else
				$error = "Email does't exist";
		}
		else
			$error = "Invalid Email";
	}
	else
	{
		$error = "Please Enter Your Email";
	}
}

?>

<html>
	<head>
		<TITLE>Forget Password</TITLE>
		<link rel="stylesheet" href="../style/home.css" />
	</head>
	<body>
		<?php if($section == 'code') {?>
		Recuperation de mot de passe pour <?= $_SESSION['mail'] ?>
		<br />
		<form method="POST" action=".">
				<label for="code">Password :</label><input id="code" type="text" name="verif_code" placeholder="Code de recuperation">
				<br />
				<br />
				<input type="submit" name="verif_submit" value="send">
			</form>
			<?php } else if ($section =='changemdp') {?>
			Nouveau Mot de passe pour  <?= $_SESSION['mail'] ?>
		<br /><br />
		<form method="POST" action=".">
				<label for="mdp">Password :</label><input id="mdp" type="Password" name="changemdp" placeholder="Nouveau mot de passe">
				<br />
				<br />
				<label for="conf">Confirmation :</label><input id="conf" type="Password" name="conf" placeholder="Confirmation de mot de passe">
				<input type="submit" name="change_submit" value="send">
			</form>
		<?php } else { ?>
		<form method="POST" action=".">
				<label for="mail">Mail :</label><input id="mail" type="email" name="mail" placeholder="Your Email">
				<br />
				<br />
				<input type="submit" name="submit" value="send">
			</form>
			<?php }?>
			<?php if(isset($error)){echo '<span style="color:red">'.$error.'</span>';} ?>
	</body>
</html>

<?php
	if (isset($_POST['verif_submit'], $_POST['verif_code']))
	{
		if (!empty($_POST['verif_code']))
		{
			$verif_code = htmlspecialchars($_POST['verif_code']);
			$verif_req = $bdd->prepare('SELECT * FROM recuperaion where mail = ? AND code = ?');
			$veriv_req->execute(array($_SESSION['mail'], $verif_code));
			if ($verif_req == 1)
			{
				$del_req = $bdd->prepare('DELETE FROM recuperation WHERE mail = ?');
				$del_req->execute($array($_SESSION['mail']));
				header('location:forget.php?section=changemdp');

			}
			else
				$error = "Invalid Code";
		}
		else
			$error = "Enter Your Password Of Confirmation";
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
					$ins_mdp = $bdd->prepare('UPDATE mmbres SET mot_de_passe = ? WHERE mail = ?');
					$ins_mdp->execute(array($mdp, $_SESSION['mail']));
					header('location:home.html');
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

<?php
include "mail.php";
include '../config/conection.php';
if (isset($_POST['submit']))
{
	$pseudo = htmlspecialchars($_POST['login']);
	$mail = htmlspecialchars($_POST['mail']);
	$mail2 = htmlspecialchars($_POST['mailconf']);
	$mdp = sha1($_POST['passwd']);
	$mdp2 = sha1($_POST['passwd2']);
	if (!empty($_POST['login']) AND !empty($_POST['mail']) AND !empty($_POST['mailconf']) AND !empty($_POST['passwd'])
		AND !empty($_POST['passwd2']))
		{
			$pseudolen = strlen($pseudo);
			if ($pseudolen <= 255)
			{
				$reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
				$reqpseudo->execute(array($pseudo));
				$pseudoexist = $reqpseudo->rowCount();
				if ($pseudoexist == 0)
				{
					if ($mail == $mail2)
					{
						if (filter_var($mail, FILTER_VALIDATE_EMAIL))
						{
							$reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
							$reqmail->execute(array($mail));
							$mailexist = $reqmail->rowCount();
							if ($mailexist == 0)
							{
								if ($mdp == $mdp2)
								{
									$longkey = 15;
									$key = "";
									for($i=1; $i < $longkey; $i++)
									{
										$key .= mt_rand(0, 9);
									}
									$insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, mdp, confirmekey) VALUES (?, ?, ?, ?)");
									$insertmbr->execute(array($pseudo, $mail, $mdp, $key));
									$string = 'pseudo='.urlencode($pseudo).'&key='.$key;
									$message='<html>
									<head>
									<title>Confirmez Votre Mail :)</title>
									</head>
									<body>
									<div align="center">
										<a href="http://localhost:8080/camagru/src/confirmation.php?'.$string.'"> Confirmez Votre Compte </a>
									</div>
									</body>
									</html>';
									mail($mail, "Confirmation De Creation De Compte", $message, $header);
									header('Location: ../index.php');
								}
								else
									$error = "Vos mots de passes ne correspondent pas !";
							}
							else
								$error = "Adresse mail de déja utilisee !";
						}
						else
							$error = "Votre adresse mail n'est pas valide !";
					}
					else
						$error = "Votre adresses mail ne correspondent pas !";
				}
				else
					$error = "Votre pseudo existe deja";
			}
			else
				$error = "Votre pseudo ne doit pas depasser 255 caracteres !";
		}
	else
	{
		$error = "Tous les champs doivent être completes !";
	}
}
?>
<html>
	<head>
		<TITLE>Registration</TITLE>
		<link rel="stylesheet" href="../style/register.css" />
		<meta name="viewport" content="width=device-width initial-scale=1.0" />
		<link rel="stylesheet" href="../style/register.css" />
	</head>
	<body>
		<div class="first">
		<img class="logo" src="../img/logo42.png" alt="logo42" title="42">
		</div>
		<div class="second">
		<table align="center">
		<tr>
		<td><img class="fleche" src="../img/fleche.png" alt="inscription" title="inscription"></td>
		<td><form method="POST" action="">
			<table>
			<tr>
			<td align="right"><label for="login">Nom :</label></td>
			<td><input id="login" type="text" name="login" placholder="login" value="<?php if (isset($pseudo)) {echo $pseudo;}?>"></td>
			</tr>
			<tr>
			<td align="right"><label for="mail">Mail :</label></td>
			<td><input id="mail" type="email" name="mail" placholder="mail" value="<?php if (isset($mail)) {echo $mail;}?>"></td>
			</tr>
			<tr>
				<td align="right"><label for="mailconf">Confirmation Mail :</label></td>
				<td><input id="mailconf" type="email" name="mailconf" placholder="Confirmez votre mail" value="<?php if (isset($mail2)) {echo $mail2;}?>"></td>
			</tr>
			<tr>	
			<td align="right"><label for="pass">Mot De Passe : </label></td>
			<td><input id="pass" type="password" name="passwd" placholder="Votre Mot de passe"></td>
			</tr>
			<tr>	
					<td align="right"><label for="pass2">Confirmation Mot De Passe : </label></td>
					<td><input id="pass2" type="password" name="passwd2" placholder="Votre Mot de passe" ></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td> <br /></td>
				<td><input type="submit" name="submit" value="Envoyer"></td>
			</tr>
			</table>
			<br />
			<br />
			<img class="inscrip" src="../img/inscrip.png" alt="inscription" title="inscription">
			</form>
			</td>
</tr>
</table>
		<div>
			<?php
			if (isset($error))
			{
				echo '<p style="color:red;dislay:block" align="center">'.$error.'</p>';
			}
			?>
	</body>
</html>
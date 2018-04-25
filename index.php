<?php

/* index: check si un useur est actif envoie sur une page home en fonction */
	session_start();
	if ($_SESSION['user'] == "" || $_SESSION['user'] == NULL)
	{
		header('location: ./src/home.php');
	}
	else
		header('location: ./src/user_home.php?id='.$_SESSION['id']);
?>
<?php

/* index: check si un useur est actif envoie sur une page home en fonction */
	session_start();
	if ($_SESSION['logged_on_user'] == "" || $_SESSION['logged_on_user'] == NULL)
	{
		header('location: ./src/home.html');
		return;
	}
	else
		header('location: ./src/user_home.php');
?>
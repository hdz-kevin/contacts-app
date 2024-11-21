<?php

session_start();

if (!isset($_SESSION["user"])) {
	header("Location: /contacts-app/login.php");
	return;
}

session_start();
session_destroy();

header("Location: /contacts-app/index.php");

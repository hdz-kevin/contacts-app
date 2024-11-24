<?php

session_start();

if (!isset($_SESSION["user"])) {
	header("Location: /contacts-app/login.php");
	return;
}

require "database/conn.php";
require "http-responses.php";
require "functions.php";

$contact_id = $_GET["id"] ?? null;

if (is_null($id) || !is_numeric($id)) {
	http_response_code(400);
	echo "HTTP 400 Bad Request";
	return;
}

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
$statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
	http_response_code(404);
	echo "HTTP 404 Not Found";
	return;
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);

if ((int) $contact["user_id"] !== (int) $_SESSION["user"]["id"]) {
	http_response_code(403);
	echo "HTTP 403 Unauthorized";
	return;
}

$conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);

header("Location: /contacts-app/");

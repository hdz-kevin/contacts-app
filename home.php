<?php

session_start();

if (!isset($_SESSION["user"])) {
	header("Location: /contacts-app/login.php");
	return;
}

require "functions.php";
require "database/conn.php";

$contacts = $conn->query("SELECT * FROM contacts")->fetchAll(PDO::FETCH_ASSOC);

// $contacts = file_exists("contacts.json")
				// ? json_decode(file_get_contents("contacts.json"), true)
				// : [];

?>

<?php require "partials/header.php" ?>

<main>
	<div class="container pt-4 p-3">
		<div class="row">
			<?php if (count($contacts) == 0) : ?>
				<div class="col-md-4 mx-auto">
					<div class="card card-body text-center">
						<p>No contacts saved yet</p>
						<a href="/contacts-app/add.php">Add One!</a>
					</div>
				</div>
			<?php endif; ?>
			<?php foreach ($contacts as $contact) : ?>
				<div class="col-md-4 mb-3">
					<div class="card text-center">
						<div class="card-body">
							<h3 class="card-title text-capitalize"><?= $contact["name"] ?></h3>
							<p class="m-2"><?= $contact["phone_number"] ?></p>
							<?php if (array_key_exists("email", $contact)): ?>
								<p class="m-2"><?= $contact["email"] ?></p>
							<?php endif ?>
							<a href="/contacts-app/edit.php?id=<?= $contact["id"] ?>" class="btn btn-secondary mb-2 mt-2">Edit Contact</a>
							<a href="/contacts-app/delete.php?id=<?= $contact["id"] ?>" class="btn btn-danger mb-2 mt-2">Delete Contact</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php require "partials/footer.php" ?>

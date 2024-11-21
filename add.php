<?php

session_start();

if (!isset($_SESSION["user"])) {
	header("Location: /contacts-app/login.php");
	return;
}

require "functions.php";
require "database/conn.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$contact = [
		"name" => trim($_POST["name"] ?? ""),
		"phone_number" => trim($_POST["phone_number"] ?? ""),
		"email" => trim($_POST["email"] ?? ""),
	];

	if (empty($contact["name"]) || empty($contact["phone_number"])) {
		$error = "Name and phone number are required";
	} else if (!is_numeric($contact["phone_number"]) || strlen($contact["phone_number"]) < 8) {
		$error = "Invalid phone number format";
	} else if (!empty($contact["email"]) && !filter_var($contact["email"], FILTER_VALIDATE_EMAIL)) {
		$error = "Invalid email format";
	} else {
		$sql = "INSERT INTO contacts (name, phone_number, email) VALUES (:name, :phone_number, :email)";
		
		$statement = $conn->prepare($sql);
		$statement->execute([
			":name" => $contact["name"],
			":phone_number" => $contact["phone_number"],
			":email" => $contact["email"] ?: null,
		]);

		// $contacts = file_exists("contacts.json")
						// ? json_decode(file_get_contents("contacts.json"), true)
						// : [];

		// $contacts[] = $contact;

		// file_put_contents("contacts.json", json_encode($contacts));
		// chmod("contacts.json", 0666);

		header("Location: /contacts-app/home.php");
	}
}

?>

<?php require "partials/header.php" ?>

<main>
	<div class="container pt-5">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">Add New Contact</div>
					<div class="card-body">
						<?php if (!is_null($error)): ?>
							<p class="text-danger"><?= $error ?></p>
						<?php endif ?>
						<form method="POST" action="add.php">
							<div class="mb-3 row">
								<label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
								<div class="col-md-6">
									<input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
								<div class="col-md-6">
									<input id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="tel" autofocus>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
								<div class="col-md-6">
									<input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
								</div>
							</div>

							<div class="mb-3 row">
								<div class="col-md-6 offset-md-4">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php require "partials/footer.php" ?>

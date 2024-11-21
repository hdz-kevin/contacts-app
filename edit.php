<?php

session_start();

if (!isset($_SESSION["user"])) {
	header("Location: /contacts-app/login.php");
	return;
}

require "functions.php";
require "database/conn.php";
require "http-responses.php";

$id = $_GET["id"] ?? null;

if (is_null($id)) {
	return $http_responses["bad request"]("Bad Resquest: Missing query parameters");
}

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
$statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
	return $http_responses["not found"]();
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);


$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$contact["name"] = trim($_POST["name"] ?? "");
	$contact["phone_number"] = trim($_POST["phone_number"] ?? "");
	$contact["email"] = trim($_POST["email"] ?? "");

	if (empty($contact["name"]) || empty($contact["phone_number"])) {
		$error = "Name and phone number are required";
	} else if (!is_numeric($contact["phone_number"]) || strlen($contact["phone_number"]) < 8) {
		$error = "Invalid phone number format";
	} else if (!empty($contact["email"]) && !filter_var($contact["email"], FILTER_VALIDATE_EMAIL)) {
		$error = "Invalid email format";
	} else {
		$conn->prepare("UPDATE contacts SET name = :name, phone_number = :phone_number, email = :email WHERE id = :id")
			 ->execute([
		         ":name" => $contact["name"],
				 ":phone_number" => $contact["phone_number"],
				 ":email" => $contact["email"] ?: null,
				 ":id" => $contact["id"],
			 ]);

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
						<form method="POST" action="edit.php?id=<?= $contact["id"] ?>">
							<!-- Enviar el id como contenido del post -->
							<!-- <input value="<//?= $contact["id"] ?>" type="hidden" name="id"> -->
							<div class="mb-3 row">
								<label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
								<div class="col-md-6">
									<input value="<?= $contact["name"] ?>" id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
								<div class="col-md-6">
									<input value="<?= $contact["phone_number"] ?>" id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="tel" autofocus>
								</div>
							</div>

							<div class="mb-3 row">
								<label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
								<div class="col-md-6">
									<input value="<?= $contact["email"] ?>" id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
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

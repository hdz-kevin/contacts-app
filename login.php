<?php

session_start();

if (isset($_SESSION["user"])) {
	header("Location: /contacts-app/home.php");
	return;
}

require "functions.php";
require "database/conn.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $body = (object) [
        "email" => trim($_POST["email"] ?? ""),
        "password" => trim($_POST["password"] ?? ""),
    ];

    if (in_array("", [$body->email, $body->password])) {
        $error = "Please fill all the fields";
    } else if (!filter_var($body->email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email format is incorrect";
    } else {
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $statement->execute([":email" => $body->email]);
        $user = $statement->fetch(PDO::FETCH_OBJ);

        if ($statement->rowCount() == 0 || !password_verify($body->password, $user->password)) {
            $error = "Invalid Credentials";
        } else {
            session_start();

            $_SESSION["user"] = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
            ];

            header("Location: /contacts-app/home.php");
        }
    }
}

?>

<?php require "partials/header.php" ?>

<main>
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <?php if (!is_null($error)): ?>
                            <p class="text-danger"><?= $error ?></p>
                        <?php endif ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3 row">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
                                
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" autocomplete="password">
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


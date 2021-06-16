<?php

include_once("includes/db.php");
include_once("includes/header.php");
?>


<!-- Navbar -->
<?php
include("includes/navbar.php");

?>
<!-- Navbar -->
<?php
// LOGIN USER
if (isset($_SESSION['username'])) {
    header('location: home-page.php');
};
$errors = array();
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Unesite korisničko ime");
    }
    if (empty($password)) {
        array_push($errors, "Unesite lozinku");
    }

    if (count($errors) == 0) {
        $password = md5($password);

        $sql = "SELECT * FROM users WHERE username='$username' AND user_password='$password'"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->get_result();
        $row = mysqli_fetch_assoc($results);
        if ($row != null) {
            $user_role = $row['user_role'];
            $user_id = $row['user_id'];
        }


        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            // $_SESSION['success'] = "You are now logged in";
            if ($user_role == 'Admin') {
                $_SESSION['role'] = 'Admin';
            }
            header('location: home-page.php');
        } else {
            array_push($errors, "Pogrešno korisničko ime ili lozinka");
        }
    }
}

?>

</header>
<!--Main Navigation-->

<!--Main layout-->
<main class="mt-5 pt-5">
    <div class="container ">




        <div class="row justify-content-center">
            <!-- Material form login -->

            <div class="col-lg-6 col-md-12 mb-4 ">
                <div class="card">
                    <h5 class="card-header danger-color white-text text-center py-4">
                        <strong>Prijava</strong>
                    </h5>

                    <!--Card content-->
                    <div class="card-body px-lg-20 pt-0">

                        <!-- Form -->
                        <form class="text-center needs-validation" method="POST" style="color: #757575;" action="login.php" novalidate>

                            <!-- Email -->
                            <div class="md-form">
                                <input name="username" type="text" id="materialLoginFormEmail" class="form-control" required>
                                <label for="materialLoginFormEmail">Korisničko ime</label>
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Molimo unesite ime.
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="md-form">
                                <input name="password" type="password" id="materialLoginFormPassword" class="form-control" required>
                                <label for="materialLoginFormPassword">Lozinka</label>
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Molimo unesite lozinku.
                                </div>
                            </div>
                            <?php
                            if (count($errors) > 0) {
                            ?>
                                <div class="col align-self-center">
                                    <div class="alert alert-danger" role="alert">
                                        <?php foreach ($errors as $error) : ?>
                                            <p><?php echo $error ?></p>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>



                            <!-- Sign in button -->
                            <button name="login" class="btn btn-outline-danger btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Prijava</button>

                            <!-- Register -->
                            <p>Niste registrirani?
                                <a href="registration.php">Registracija</a>
                            </p>



                        </form>
                        <!-- Form -->

                    </div>
                </div>
            </div>
        </div>


        <!-- Material form login -->

    </div>
</main>
<!--Main layout-->

<!--Footer-->
<?php

include_once("includes/footer.php");

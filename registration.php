<?php

include_once("includes/db.php");
include_once("includes/header.php");
include("includes/functions.php");
?>


<!-- Navbar -->
<?php
include("includes/navbar.php");

?>
<!-- Navbar -->

<?php


// initializing variables
$username = "";
$email    = "";
$errors = array();


// REGISTER USER
if (isset($_POST['signup'])) {
    // receive all input values from the form

    $firstName = mysqli_real_escape_string($conn, $_POST['name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['surname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);



    $letNumCheck = array('AbCd1zyZ9', $password_1);
    foreach ($letNumCheck as $testcase) {
        if (!ctype_alnum($testcase)) {
            array_push($errors, "Lozinka se može sastojati samo od slova i brojki");
        }
    }


    if (strlen($password_1) < 6) {
        array_push($errors, "Lozinka se mora sastojati od bar 7 brojki i/ili slova");
    }

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($firstName)) {
        array_push($errors, "Ime je obavezno");
    }
    if (empty($lastName)) {
        array_push($errors, "Prezime je obavezno");
    }
    if (empty($username)) {
        array_push($errors, "Korisničko ime je obavezno");
    }
    if (empty($email)) {
        array_push($errors, "Email je obavezan");
    }
    if (empty($password_1)) {
        array_push($errors, "Lozinka je obavezna");
    }
    if (empty($password_2)) {
        array_push($errors, "Potvrda lozinke je obavezna");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "Lozinke se ne podudaraju");
    }

    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $sql = "SELECT * FROM users WHERE username='$username' OR user_email='$email' LIMIT 1"; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = mysqli_fetch_assoc($result);


    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Ovo korisničko ime već postoji");
        }

        if ($user['user_email'] === $email) {
            array_push($errors, "Email je već u upotrebi");
        }
    }



    // Image Upload
    $target_dir = "userImages/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }


    // Finally, register user if there are no errors in the form
    if (count($errors) == 0 && $uploadOk != 0) {
        $password = md5($password_1); //encrypt the password before saving in the database


        $user_image = date('dmYHis') . str_replace(" ", "", basename($_FILES["fileToUpload"]["name"]));
        $target_file = $target_dir . $user_image;
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        $user_date = date('Y-m-d H:i:s');
        $query = $conn->prepare("INSERT INTO users (user_firstname,user_lastname,username,user_email,user_password,user_image,user_date) VALUES (?,?,?,?,?,?,?)");
        $query->bind_param("sssssss", $firstName, $lastName, $username, $email, $password, $user_image, $user_date);
        $query->execute();

        /*
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Uspješna registracija i prijava";
        $_SESSION['email'] = '';
        $_SESSION['firstName'] = '';
        $_SESSION['lastName'] = '';
        */
        echo '
        <div class="alert alert-success" role="alert" style="margin-top:50px">
        Uspješna registracija,kliknite <a href="login.php" class="alert-link">ovdje</a> za prijavu.
        </div>';
        unset($_SESSION['usernameTry']);
        unset($_SESSION['email']);
        unset($_SESSION['firstName']);
        unset($_SESSION['lastName']);
    } else {
        $_SESSION['usernameTry'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
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
                <!-- Material form register -->
                <div class="card">

                    <h5 class="card-header danger-color white-text text-center py-4">
                        <strong>Registracija</strong>
                    </h5>
                    <?php
                    if (count($errors) > 0) {
                    ?>
                        <div class="col align-self-center">
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($errors as $error) : ?>
                                    <p><?php displayErrorMessage($error) ?></p>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <!--Card content-->
                    <div class="card-body px-lg-5 pt-0">

                        <!-- Form -->
                        <form enctype="multipart/form-data" class="text-center needs-validation" style="color: #757575;" method="post" action="registration.php" novalidate>

                            <div class="form-row">
                                <div class="col">
                                    <!-- First name -->
                                    <div class="md-form">
                                        <input name="name" type="text" id="materialRegisterFormFirstName" class="form-control" required oninvalid="this.setCustomValidity('Ime je obavezno!')" oninput="this.setCustomValidity('')" value="<?php if (isset($_SESSION['firstName'])) {
                                                                                                                                                                                                                                                echo $_SESSION['firstName'];
                                                                                                                                                                                                                                            } ?>">
                                        <label for=" materialRegisterFormFirstName">Ime</label>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime.
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <!-- Last name -->
                                    <div class="md-form">
                                        <input name="surname" type="text" id="materialRegisterFormLastName" class="form-control" required oninvalid="this.setCustomValidity('Prezime je obavezno!')" oninput="this.setCustomValidity('')" value="<?php if (isset($_SESSION['lastName'])) {
                                                                                                                                                                                                                                                        echo $_SESSION['lastName'];
                                                                                                                                                                                                                                                    } ?>">
                                        <label for=" materialRegisterFormLastName">Prezime</label>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite prezime.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E-mail -->
                            <div class="md-form mt-0">
                                <input name="email" type="email" id="materialRegisterFormEmail" class="form-control" required oninvalid="this.setCustomValidity('E-mail je obavezan!')" oninput="this.setCustomValidity('')" value="<?php if (isset($_SESSION['email'])) {
                                                                                                                                                                                                                                        echo $_SESSION['email'];
                                                                                                                                                                                                                                    } ?>">
                                <label for="materialRegisterFormEmail">E-mail</label>
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Unesite e-mail.
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="md-form mt-0">
                                <input name="username" type="text" id="materialRegisterFormEmail" class="form-control" required oninvalid="this.setCustomValidity('E-mail je obavezan!')" oninput="this.setCustomValidity('')" value="<?php if (isset($_SESSION['usernameTry'])) {
                                                                                                                                                                                                                                            echo $_SESSION['usernameTry'];
                                                                                                                                                                                                                                        } ?>">
                                <label for="materialRegisterFormEmail">Korisničko ime</label>
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Unesite korisničko ime.
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="md-form">
                                <input name="password_1" type="password" id="materialRegisterFormPassword" class="form-control" aria-describedby="materialRegisterFormPasswordHelpBlock" required oninvalid="this.setCustomValidity('Lozinka je obavezna!')" oninput="this.setCustomValidity('')">
                                <label for="materialRegisterFormPassword">Lozinka</label>
                                <small id="materialRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                                    Najmanje 7 znakova
                                </small>
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Unesite lozinku.
                                </div>
                            </div>


                            <!-- Password Confirm-->
                            <div class="md-form">
                                <input name="password_2" type="password" id="materialRegisterFormPassword" class="form-control" aria-describedby="materialRegisterFormPasswordHelpBlock" required oninvalid="this.setCustomValidity('Ponovljena lozinka je obavezna!')" oninput="this.setCustomValidity('')">
                                <label for="materialRegisterFormPassword">Ponovi Lozinku</label>
                                <small id="materialRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                                    Najmanje 7 znakova
                                </small>
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Ponovite lozinku.
                                </div>
                            </div>

                            <div class="md-form">
                                <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" oninvalid="this.setCustomValidity('Slika je obavezna!')" oninput="this.setCustomValidity('')" required />
                                <div class="valid-feedback">
                                    Super!
                                </div>
                                <div class="invalid-feedback">
                                    Slika je obavezna
                                </div>
                            </div>


                            <!-- Newsletter -->
                            <!--
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="materialRegisterFormNewsletter">
                                <label class="form-check-label" for="materialRegisterFormNewsletter">Subscribe to our newsletter</label>
                            </div>
                            -->
                            <!-- Sign up button -->
                            <button name="signup" class="btn btn-outline-danger btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Registracija</button>
                            <hr>
                            <!-- Terms of service -->
                            <p>Pritiskom na
                                <em>Registracija</em> slažete se sa
                                <a href="" target="_blank">uvjetima i odredbama</a>

                        </form>
                        <!-- Form -->

                    </div>

                </div>
                <!-- Material form register -->
            </div>
        </div>


        <!-- Material form login -->

    </div>
</main>
<!--Main layout-->

<!--Footer-->
<?php

include_once("includes/footer.php");

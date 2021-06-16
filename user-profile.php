<?php


include_once("includes/header.php");
include("includes/db.php");
include("includes/functions.php");

?>


<!-- Navbar -->
<?php
include("includes/navbar.php");

if (!isset($_SESSION['user_id'])) {
    header("location:home-page.php");
}


?>
<!-- Navbar -->

</header>
<!--Main Navigation-->

<!--Main layout-->
<main class="mt-5 pt-5">
    <div class="container">


        <?php



        $sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);

        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        //$user = $result->fetch_assoc(); // fetch data 
        $row = mysqli_fetch_assoc($result);

        $user_id = $row['user_id'];
        $username1 = $row["username"];
        $user_firstname = $row["user_firstname"];
        $user_lastname = $row["user_lastname"];
        $user_email = $row["user_email"];
        $user_image = $row["user_image"];
        $user_role = $row["user_role"];




        ?>



        <!--Section: Cards-->

        <section class="text-center">


            <div class="content">
                <h2 class="font-weight-bold text-white">Moji podaci</h2>
                <div class="col-lg-12 col-md-12 ">
                    <div class="card">
                        <div class="card-body justify-content-center">
                            <?php
                            $errors = array();
                            if (isset($_POST['profile_edit'])) {
                                // receive all input values from the form

                                $firstName = mysqli_real_escape_string($conn, $_POST['name']);
                                $lastName = mysqli_real_escape_string($conn, $_POST['surname']);
                                $username = mysqli_real_escape_string($conn, $_POST['username']);
                                $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
                                $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);
                                $userId = $_SESSION['user_id'];






                                if (!empty($password_1)) {
                                    $letNumCheck = array('AbCd1zyZ9', $password_1);
                                    foreach ($letNumCheck as $testcase) {
                                        if (!ctype_alnum($testcase)) {
                                            array_push($errors, "Lozinka se može sastojati samo od slova i brojki");
                                        }
                                    }


                                    if (strlen($password_1) < 6) {
                                        array_push($errors, "Lozinka se mora sastojati od bar 7 brojki i/ili slova");
                                    }
                                }

                                // form validation: ensure that the form is correctly filled ...
                                // by adding (array_push()) corresponding error unto $errors array
                                if (empty($firstName)) {
                                    array_push($errors, "Ime je obavezno");
                                }
                                if (empty($lastName)) {
                                    array_push($errors, "Prezime je obavezno");
                                }



                                if ($password_1 != $password_2) {
                                    array_push($errors, "Lozinke se ne podudaraju");
                                }

                                // first check the database to make sure
                                // a user does not already exist with the same username and/or email
                                $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1"; // SQL with parameters
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $user = mysqli_fetch_assoc($result);


                                if ($username != '') {
                                    if ($user) { // if user exists
                                        if ($user['username'] === $username) {
                                            array_push($errors, "Ovo korisničko ime već postoji");
                                        }
                                    }
                                }


                                // uploadOk = 2 => Update data without new image
                                // uploadOk = 1 => Update data with new image
                                // uploadOk = 0 => Error with image
                                $uploadOk = 2;
                                if (!empty($_FILES["fileToUpload"]["tmp_name"])) {
                                    // Image Upload
                                    $target_dir = "userImages/";
                                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                                    $uploadOk = 1;
                                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                                    // Check if image file is a actual image or fake image

                                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                                    if ($check !== false) {
                                        //echo "File is an image - " . $check["mime"] . ".";
                                        $uploadOk = 1;
                                    } else {
                                        echo "Datoteka nije slika";
                                        $uploadOk = 0;
                                    }


                                    // Check file size
                                    if ($_FILES["fileToUpload"]["size"] > 50000000) {
                                        echo "Vaša slika je prevelika";
                                        $uploadOk = 0;
                                    }

                                    // Allow certain file formats
                                    if (
                                        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                        && $imageFileType != "gif"
                                    ) {
                                        echo "Dozvoljeni su samo sljedeći formati slika: JPG, JPEG, PNG & GIF ";
                                        $uploadOk = 0;
                                    }
                                }



                                // Finally, register user if there are no errors in the form
                                if (count($errors) == 0 && $uploadOk == 1) {

                                    $user_image = date('dmYHis') . str_replace(" ", "", basename($_FILES["fileToUpload"]["name"]));
                                    $i = 1;
                                    while (file_exists($target_dir . $user_image)) {
                                        $user_image = $user_image . $i;
                                        $i++;
                                    }

                                    $target_file = $target_dir . $user_image;
                                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                                    if ($password_1 != '') {
                                        if ($username != '') {
                                            $password = md5($password_1); //encrypt the password before saving in the database
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,username = ?,user_password = ?,user_image = ?");
                                            $query->bind_param("sssss", $firstName, $lastName, $username, $password, $user_image);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        } else {
                                            $password = md5($password_1); //encrypt the password before saving in the database
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,user_password = ?,user_image = ?");
                                            $query->bind_param("sssss", $firstName, $lastName, $password, $user_image);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        }
                                    } else {
                                        if ($username != '') {
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,username = ?,user_image = ?");
                                            $query->bind_param("sssss", $firstName, $lastName, $username,  $user_image);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        } else {
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,user_image = ? WHERE user_id = $userId");
                                            $query->bind_param("sss", $firstName, $lastName,  $user_image);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        }
                                    }
                                } else if (count($errors) == 0 && $uploadOk == 2) {

                                    if ($password_1 != '') {
                                        if ($username != '') {
                                            $password = md5($password_1); //encrypt the password before saving in the database
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,username = ?,user_password = ? WHERE user_id = ?");
                                            $query->bind_param("sssss", $firstName, $lastName, $username,  $password, $userId);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        } else {
                                            $password = md5($password_1); //encrypt the password before saving in the database
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,user_password = ? WHERE user_id = ?");
                                            $query->bind_param("ssss", $firstName, $lastName, $password, $userId);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        }
                                    } else {
                                        if ($username != '') {
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?,username = ? WHERE user_id = ?");
                                            $query->bind_param("sssi", $firstName, $lastName, $username,  $userId);
                                            $query->execute();
                                            $_SESSION['username'] = $username;
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                                        } else {
                                            $query = $conn->prepare("UPDATE users SET user_firstname = ?,user_lastname = ?  WHERE user_id = ?");
                                            $query->bind_param("ssi", $firstName, $lastName,  $userId);
                                            $query->execute();
                                            $successMessage = 'Podaci uspješno promjenjeni';
                                            displaySuccessMessage($successMessage);
                            ?>


                            <?php
                                        }
                                    }
                                } else {
                                    foreach ($errors as $result) {

                                        displayErrorMessage($result);
                                    }
                                }
                            }
                            ?>
                            <form class="needs-validation" enctype="multipart/form-data" action="" method="post" name="edit_profile" novalidate>

                                <div class="form-row">
                                    <div class="col-lg-5 col-md-12  mb-3 ml-5 ">
                                        <label for="inputAddress">Korisničko ime</label>
                                        <input name="username" type="text" class="form-control " id="post_title" oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')" placeholder="<?php echo $_SESSION['username'] ?>">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite korisničko ime.
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-12 ml-5">
                                        <label for="inputAddress">E-mail</label>
                                        <input name="email" type="email" class="form-control " id="post_title" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')" value="<?php echo $user_email  ?>" readonly>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite e-mail.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-lg-5 col-md-12 ml-5">
                                        <label for="inputAddress">Ime</label>
                                        <input name="name" type="text" class="form-control " id="post_title" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')" value="<?php echo $user_firstname  ?>">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime.
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-12 ml-5">
                                        <label for="inputAddress">Prezime</label>
                                        <input name="surname" type="text" class="form-control " id="post_title" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')" value="<?php echo $user_lastname  ?>">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite prezime.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">

                                </div>
                                <div class="form-row">
                                    <div class="col-lg-5 col-md-12 ml-5">
                                        <label for="inputAddress">Nova lozinka</label>
                                        <input name="password_1" type="password" class="form-control " id="post_title" oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite prezime.
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-12 ml-5">
                                        <label for="inputAddress">Ponovljena nova lozinka</label>
                                        <input name="password_2" type="password" class="form-control " id="post_title" oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite prezime.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-lg-5 col-md-12 ml-5 mt-3">
                                        <label for="inputAddress">Ovlast</label>
                                        <input name="post_title" type="email" class="form-control " id="post_title" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')" readonly value="<?php echo $user_role  ?>">

                                    </div>
                                </div>
                                <div class=" form-row">
                                    <div class="col-lg-5 col-md-12 mt-4 ml-5">
                                        <label for="current">Trenutna Slika</label>
                                        <img src="userImages/<?php echo $user_image ?>" alt="" style="width: 400px; heigth:200px">

                                    </div>
                                    <div class="col-lg-5 col-md-12 ml-5">

                                        <div class="md-form">
                                            <p class="float-left">Nova slika</p>
                                            <input class="form-control-file" type="file" name="fileToUpload" id="uploadImageFile" onchange="showImageHereFunc();">
                                        </div>

                                        <label for="showImageHere">Preview slike</label>

                                        <div id="showImageHere"></div>
                                    </div>
                                </div>


                                <div class=" form-row">

                                </div>

                                <div class=" form-row">
                                    <div class="col-lg-10 col-md-12 ml-5 mt-2">
                                        <button type="submit" class="btn btn-danger" name="profile_edit" value="Add Post">Spremi promjene</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>





        </section>

        <!--Section: Cards-->

    </div>
</main>
<!--Main layout-->
<script>
    function showImageHereFunc() {
        $("#showImageHere").empty();
        var total_file = document.getElementById("uploadImageFile").files.length;
        var last = total_file - 1;

        $("#showImageHere").append(
            "<img src='" +
            URL.createObjectURL(event.target.files[last]) +
            "' height='300px' width=' 500px' style='border-style: solid;  '>"
        );

    }
</script>
<!--Footer-->
<?php

include_once("includes/footer.php");

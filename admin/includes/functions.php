<?php

include("includes/db.php");



function createPost()
{

    global $conn;
    $post_title = htmlentities($_POST['post_title']);
    $post_content = htmlentities($_POST['post_content']);
    $post_date = date('Y-m-d H:i:s');
    $post_author = $_SESSION['user_id'];
    $post_category_id = htmlentities($_POST['category']);
    $post_manufacturer = htmlentities($_POST['manufacturer']);
    if (isset(($_POST['allow']))) {
        $post_status = 'Objavljen';
    } else {
        $post_status = 'Zabranjen';
    }




    // IMAGE UPLOAD


    /*

    for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {
        $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
        $fileName = $_FILES["uploadImageFile"]["name"][$i];
        $folder = "./images/";
        move_uploaded_file($uploadfile, "$folder" . $fileName);
        $stmtImages = $conn->prepare("INSERT INTO images (name) VALUES (?)");
        $stmtImages->bind_param("s", $fileName);
        $stmtImages->execute();

        
    }
    $_FILES["uploadImageFile"] = null;

    */

    $checker = 0;
    $target_dir = "./images/";
    for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {
        $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
        $fileName = $_FILES["uploadImageFile"]["name"][$i];
        $target_file = $target_dir . $fileName;
        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        // Check if image file is a actual image or fake image

        $check = getimagesize($uploadfile);
        if ($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger" role="alert">
        Datoteka nije slika
      </div>';;
            $uploadOk = 0;
        }


        // Check if file already exists
        /*if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        */
        // Check file size
        if ($_FILES["uploadImageFile"]["size"][$i] > 50000000) {
            echo '<div class="alert alert-danger" role="alert">
        Slika je prevelika
      </div>';;
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo '<div class="alert alert-danger" role="alert">
        Samo JPG, JPEG, PNG & GIF formati su dopušteni.
      </div>';;


            $uploadOk = 0;
        }


        if ($uploadOk === 1) {
            $checker++;
        }

        /*
        move_uploaded_file($uploadfile, "$target_dir" . $fileName);
        $stmtImages = $conn->prepare("INSERT INTO images (name) VALUES (?)");
        $stmtImages->bind_param("s", $fileName);
        $stmtImages->execute();
        */
    }

    // IMAGE UPLOAD
    if ($checker === count($_FILES["uploadImageFile"]["name"])) {
        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO posts (post_category_id,post_title,post_author,post_date,post_content,manufacturer) VALUES (?, ?, ?,?,?,?)");
        $stmt->bind_param("isisss", $post_category_id, $post_title, $post_author, $post_date, $post_content, $post_manufacturer);
        if ($stmt->execute()) {
            $post_id = mysqli_insert_id($conn);

            $target_dir = "./images/";
            for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {

                $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
                $fileName = $_FILES["uploadImageFile"]["name"][$i];
                $target_file = $target_dir . $uploadfile;
                $rng = 1;
                $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES["uploadImageFile"]["name"][$i]));
                while (file_exists($target_dir . $newfilename)) {
                    $newfilename = $newfilename . $rng;
                    $rng++;
                }


                if (move_uploaded_file($uploadfile, "$target_dir" . $newfilename)) {

                    $stmtImages = $conn->prepare("INSERT INTO images (name,post_id) VALUES (?,?)");
                    $stmtImages->bind_param("si", $newfilename, $post_id);
                    $stmtImages->execute();
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                    Greška sa postavljanjem slike.
                        </div>';
                }
            }
            echo '<div class="alert alert-success" role="alert">
            Objava uspješno postavljena.
          </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
        Greška sa postavljanjem objave.
      </div>';;
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Greška sa postavljanjem slike.
      </div>';;
    }
}

function updatePost()
{

    global $conn;
    $id = htmlentities($_POST['post_id']);
    $post_title = htmlentities($_POST['post_title']);
    $post_content = htmlentities($_POST['post_content']);
    $post_category_id = htmlentities($_POST['category']);
    $post_manufacturer = htmlentities($_POST['manufacturer']);

    if ($post_category_id != 0) {
        $stmt = $conn->prepare("UPDATE posts SET post_category_id = ?,post_title= ?,post_content=?,manufacturer=? WHERE post_id = ?");
        $stmt->bind_param("isssi", $post_category_id, $post_title, $post_content, $post_manufacturer, $id);
        $stmt->execute();
        return true;
    } else {
        $stmt = $conn->prepare("UPDATE posts SET post_title= ?,post_content=?,manufacturer=? WHERE post_id = ?");
        $stmt->bind_param("sssi",  $post_title, $post_content, $post_manufacturer, $id);
        $stmt->execute();
        return true;
    }
}


function addImages()
{

    global $conn;
    $post_id = htmlentities($_POST['post_id']);
    $checker = 0;
    $target_dir = "./images/";
    for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {
        $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
        $fileName = $_FILES["uploadImageFile"]["name"][$i];
        $target_file = $target_dir . $fileName;
        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        // Check if image file is a actual image or fake image

        $check = getimagesize($uploadfile);
        if ($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger" role="alert">
        Datoteka nije slika
      </div>';;
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["uploadImageFile"]["size"][$i] > 50000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo '<div class="alert alert-danger" role="alert">
        Dopušteni su samo sljedeći formati: JPG,JPEG,PNG i GIF.
      </div>';;


            $uploadOk = 0;
        }


        if ($uploadOk === 1) {
            $checker++;
        }

        /*
        move_uploaded_file($uploadfile, "$target_dir" . $fileName);
        $stmtImages = $conn->prepare("INSERT INTO images (name) VALUES (?)");
        $stmtImages->bind_param("s", $fileName);
        $stmtImages->execute();
        */
    }

    // IMAGE UPLOAD
    if ($checker === count($_FILES["uploadImageFile"]["name"])) {

        $target_dir = "./images/";
        for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {

            $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
            $fileName = $_FILES["uploadImageFile"]["name"][$i];
            $target_file = $target_dir . $uploadfile;
            $rng = 1;
            $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES["uploadImageFile"]["name"][$i]));
            while (file_exists($target_dir . $newfilename)) {
                $newfilename = $newfilename . $rng;
                $rng++;
            }


            if (move_uploaded_file($uploadfile, "$target_dir" . $newfilename)) {

                $stmtImages = $conn->prepare("INSERT INTO images (name,post_id) VALUES (?,?)");
                $stmtImages->bind_param("si", $newfilename, $post_id);
                $stmtImages->execute();
            } else {
                echo '<div class="alert alert-danger" role="alert">
                Greška sa postavljanjem slike.
              </div>';;
            }
        }
        return true;
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Greška sa postavljanjem slike.
      </div>';;
    }
}




function createCategory()
{

    global $conn;
    $cat_title = htmlentities($_POST['category']);
    $stmt = $conn->prepare("INSERT INTO categories (cat_title) VALUES (?)");
    $stmt->bind_param("s", $cat_title);
    $stmt->execute();
    return true;
}



function displaySuccessMessage()
{
    echo
        '<div class="alert alert-success" role="alert">
                                                <strong> Promjene uspješno spremljene </strong>
                                            </div>';
}



function confirmQuery($create_post_query)
{
    global $conn;
    if (!$create_post_query) {
        die("QUERY FAILED" . mysqli_error($conn));
    }
}


function confirmQuery1($create_post_query)
{
    global $conn;
    if (!$create_post_query) {
        echo
            '<div class="alert alert-danger" role="alert">
                                                <strong> Greška u postavljanju objave </strong>
                                            </div>';
        die("QUERY FAILED" . mysqli_error($conn));
    } else {
        echo '<div class="alert alert-success" role="alert">
    Objava je uspješno dodana
  </div>';
    }
}

<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");

?>


<form class="needs-validation" action="" method="post" enctype="multipart/form-data" name="add_post" novalidate>

    <div class="form-group">
        <label for="inputAddress">Naslov</label>
        <input name="post_title" type="text" class="form-control " id="post_title" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
        <div class="valid-feedback">
            Super!
        </div>
        <div class="invalid-feedback">
            Molimo unesite naslov.
        </div>
    </div>
    <div class=" form-group">
        <label for="exampleFormControlTextarea1">Sadržaj</label>
        <textarea name="post_content" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"></textarea>
        <div class="valid-feedback">
            Super!
        </div>
        <div class="invalid-feedback">
            Molimo unesite sadržaj.
        </div>
    </div>

    <div class=" form-row">
        <div class="form-group col-md-4">
            <label for="inputState">Proizvođač: &nbsp;</label>
            <div class="form-check form-check-radio form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="manufacturer" id="inlineRadio1" value="AMD" checked> AMD
                    <span class="form-check-sign"></span>
                </label>
            </div>
            <div class="form-check form-check-radio form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="manufacturer" id="inlineRadio2" value="Nvidia"> Nvidia
                    <span class="form-check-sign"></span>
                </label>
            </div>
            <div class="form-check form-check-radio form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="manufacturer" id="inlineRadio3" value="Intel"> Intel
                    <span class="form-check-sign"></span>
                </label>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label for="inputState">Kategorija</label>
            <select id="inputState" class=" form-control select-purple" name="category">

                <?php
                $sql = "SELECT * FROM categories"; // SQL with parameters
                $stmt = $conn->prepare($sql);

                $stmt->execute();
                $result = $stmt->get_result(); // get the mysqli result
                $user = $result->fetch_assoc(); // fetch data 
                while ($row = mysqli_fetch_assoc($result)) {

                    $cat_title = $row["cat_title"];
                    $cat_id = $row["cat_id"];

                ?>

                    <option value=<?php echo $cat_id  ?>><?php echo $cat_title ?></option>

                <?php
                }

                ?>



            </select>
        </div>

    </div>
    <div class=" form-row">
        <label for="uploadImageFile"> &nbsp; Slike: &nbsp; </label>
        <input class="form-control" type="file" id="uploadImageFile" name="uploadImageFile[]" onchange="showImageHereFunc();" multiple required />
        <label for="showImageHere">Preview slika</label>
        <div class="valid-feedback">
            Super!
        </div>
        <div class="invalid-feedback">
            Slike su obavezne.
        </div>
        <div id="showImageHere"></div>
    </div>

    <div class="form-group">
        <div class="form-check">
            <label class="form-check-label">
                <input name="allow" class="form-check-input" type="checkbox" value="">
                Objavi ga odmah
                <span class="form-check-sign">
                    <span class="check"></span>
                </span>
            </label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary" name="create_post" value="Add Post">Kreiraj</button>
</form>


<?php

//include_once("includes/admin-fixed-plugin.php");
include_once("includes/admin-footer.php");

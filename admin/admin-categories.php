<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();
?>




<body class="">
    <div class="wrapper">
        <div class="sidebar">
            <!--
            Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
             -->
            <?php
            include("includes/admin-sidebar.php");
            ?>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <?php
            include("includes/admin-navbar.php");
            ?>
            <!-- End Navbar -->
            <div class="content">
                <?php
                if (isset($_POST['create_category'])) {
                    $queryResult = createCategory();
                    confirmQuery($queryResult);
                    echo '<div class="alert alert-success" role="alert">
Kategorija je uspješno dodana.
</div>';
                }
                if (isset($_POST['edit_category'])) {
                    if (isset($_GET['edit_cat'])) {
                        $category = $_POST['category'];
                        $cat_id = $_GET['edit_cat'];
                        $stmtImages = $conn->prepare("UPDATE categories SET cat_title = ? WHERE cat_id = ? ");
                        $stmtImages->bind_param("si", $category, $cat_id);
                        $stmtImages->execute();
                        header("location: admin-categories.php");
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
Nije odabrana kategorija za promjenu.
</div>';
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <form action="" method="post" enctype="multipart/form-data" name="add_category" class="needs-validation" novalidate>
                                    <div class="form-group">
                                        <label for="category">Nova kategorija</label>
                                        <input type="text" name="category" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime kategorije.
                                        </div>
                                    </div>
                                    <button type="submit" name="create_category" class="btn btn-primary">Dodaj</button>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">

                                <form action="" method="post" enctype="multipart/form-data" name="add_category" class="needs-validation" novalidate>
                                    <div class="form-group">
                                        <label for="category">Promjena imena kategorije</label>
                                        <input type="text" name="category" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required value="<?php if (isset($_GET['cat_title'])) echo $_GET['cat_title'] ?>">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo odaberite kategoriju.
                                        </div>
                                    </div>
                                    <button type="submit" name="edit_category" class="btn btn-primary">Spremi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h4 class="card-title"> Kategorije</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table tablesorter " id="">
                                        <thead class=" text-primary">
                                            <tr>
                                                <th>
                                                    ID
                                                </th>
                                                <th>
                                                    Ime kategorije
                                                </th>
                                                <th class="text-center">Promjena</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM categories"; // SQL with parameters
                                            $stmt = $conn->prepare($sql);

                                            $stmt->execute();
                                            $result = $stmt->get_result(); // get the mysqli result
                                            //$user = $result->fetch_assoc(); // fetch data
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['cat_id'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['cat_title'] ?>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <a href="admin-categories.php?edit_cat=<?php echo $row['cat_id'] ?>&cat_title=<?php echo $row['cat_title'] ?>"><button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon">
                                                                <i class="tim-icons icon-settings"></i>
                                                            </button></a>
                                                    </td>
                                                    <!--
                                                    <td class="td-actions text-center">
                                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon">
                                                            <i class="tim-icons icon-simple-remove"></i>
                                                        </button>
                                                    </td>
                                                    -->
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>
    </div>




    <?php

    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

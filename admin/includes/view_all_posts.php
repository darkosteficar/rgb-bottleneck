<?php
if (isset($_GET['delete'])) {
    $post_id = $_GET['delete'];
    $sql = "DELETE FROM posts WHERE post_id = $post_id "; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $prevUrl = $_SESSION['prevUrl'];
    header("location: $prevUrl ");
    unset($_SESSION['prevUrl']);
    echo '<div class="alert alert-success" role="alert">
    Objava je uspješno obrisana
  </div>';
}


?>

<?php
if (isset($_GET['deleteImage'])) {
    $image_id = $_GET['deleteImage'];

    $getName = $conn->prepare('SELECT * FROM images WHERE id = ?');
    $getName->bind_param('i', $image_id);
    $getName->execute();
    $resultImage = $getName->get_result();
    $imageName = mysqli_fetch_assoc($resultImage);

    $stmtImages = $conn->prepare("DELETE FROM images WHERE id = ? ");
    $stmtImages->bind_param("i", $image_id);
    if ($stmtImages->execute()) {
        unlink('images/' . $imageName['name']);

        $_SESSION['success'] = 'Slika je uspješno obrisana';
        $prevUrl = $_SESSION['prevUrl'];
        header("location: $prevUrl ");
        unset($_SESSION['prevUrl']);
    } else {
        echo '<div class="alert alert-danger" role="alert">
    Greška u brisanju.
  </div>';
    }
}


?>


<?php
if (isset($_POST['edit_post'])) {
    //$post_id = $_GET['edit'];

    $queryResult = updatePost();
    confirmQuery($queryResult);
    echo '<div class="alert alert-success" role="alert">
    Objava je uspješno ažurirana
  </div>';
}


?>

<?php
if (isset($_POST['add_images'])) {
    $queryResult = addImages();
    confirmQuery($queryResult);
    echo '<div class="alert alert-success" role="alert">
    Slike su uspješno dodane
  </div>';
}


?>



<table class="table  table-striped table-bordered table-hover">
    <thead>



        <tr>
            <th class="text-center">ID</th>
            <th>Naslov</th>
            <th>Kategorija</th>
            <th>Proizvođač</th>
            <th>Autor</th>
            <th>Datum</th>

            <th>Broj komentara</th>
            <th class="text-center">Akcije</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
        $sql = $conn->prepare("SELECT * FROM posts");
        $sql->execute();
        $results = $sql->get_result();
        $allRecrods = mysqli_num_rows($results);
        // Calculate total pages
        $totoalPages = ceil($allRecrods / $limit);
        // Current pagination page number
        $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        $prev = $page - 1;
        $next = $page + 1;
        // Offset
        $paginationStart = ($page - 1) * $limit;
        // Limit query
        $sql = $conn->prepare("SELECT * FROM posts LIMIT $paginationStart, $limit");
        $sql->execute();
        $resultsCat = $sql->get_result();
        //$user = $result->fetch_assoc(); // fetch data 
        while ($row = mysqli_fetch_assoc($resultsCat)) {

            $post_id = $row['post_id'];
            $post_title = $row["post_title"];
            $post_category_id = $row["post_category_id"];
            $post_author = $row["post_author"];
            $post_date = $row["post_date"];
            $post_content = $row["post_content"];
            $post_comment_count = $row["post_comment_count"];

            $manufacturer = $row["manufacturer"];


            $sql1 =  "SELECT * FROM categories WHERE cat_id = $post_category_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->execute();
            $result2 = $stmt1->get_result(); // get the mysqli result

            $sql2 =  "SELECT * FROM users WHERE user_id = $post_author";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute();
            $result3 = $stmt2->get_result(); // get the mysqli result
            if (mysqli_num_rows($result3) == 0) {
                $userAuthorName = 'Nepoznat';
            } else {
                $userAuthor = mysqli_fetch_assoc($result3);
                $userAuthorName = $userAuthor['username'];
            }


            //$query2 = "SELECT * FROM users WHERE user_id = $post_author";
            //$select_users_id = mysqli_query($connection, $query2);

        ?>

            <tr>
                <td class="text-center"><?php echo $post_id ?></td>
                <td><?php echo $post_title ?></td>
                <?php
                while ($row = mysqli_fetch_assoc($result2)) {

                    $cat_title = $row["cat_title"];

                    echo "<td>{$cat_title}</td>";
                }
                ?>
                <td><?php echo $manufacturer ?></td>
                <td><?php echo $userAuthorName ?></td>
                <td><?php echo $post_date ?></td>

                <td><?php echo $post_comment_count ?></td>
                <td class="td-actions text-center">

                    <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal3<?php echo $post_id ?>">
                        <i class="tim-icons icon-attach-87"></i>
                    </button>


                    <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal2<?php echo $post_id ?>">
                        <i class="tim-icons icon-single-02"></i>
                    </button>

                    <button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal1<?php echo $post_id ?>">
                        <i class="tim-icons icon-settings"></i>
                    </button>


                    <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal<?php echo $post_id ?>">
                        <i class="tim-icons icon-simple-remove"></i>
                    </button>

                </td>
                <?php include("modals-posts.php") ?>
            </tr>



        <?php
        }




        ?>





    </tbody>
</table>
<!--Pagination-->
<nav class="d-flex justify-content-center wow fadeIn">
    <ul class="pagination pg-blue">

        <!--Arrow left-->
        <li class="page-item <?php if ($page <= 1) {
                                    echo 'disabled';
                                } ?> ">
            <a class="page-link" href="<?php if ($page <= 1) {
                                            echo '#';
                                        } else {
                                            echo '?page=' . $prev;
                                        } ?> " aria-label="Previous">
                <span aria-hidden="true"> &lArr;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>


        <?php
        for ($i = 1; $i <= $totoalPages; $i++) {
        ?>
            <li class="page-item <?php if ($page == $i) {
                                        echo 'active';
                                    }  ?>">
                <a class="page-link" href="<?php echo 'admin-posts.php?page=' . $i ?>"><?php echo $i ?>
                    <?php if ($page == $i) {
                    ?>
                        <span class="sr-only">(current)</span>
                    <?php
                    } ?>

                </a>
            </li>

        <?php
        }

        ?>


        <li class="page-item <?php if ($page >= $totoalPages) {
                                    echo 'disabled';
                                } ?> ">
            <a class="page-link" href="<?php if ($page >= $totoalPages) {
                                            echo '#';
                                        } else {
                                            echo '?page=' . $next;
                                        } ?> " aria-label="Next">
                <span aria-hidden="true">&rArr;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>
<!--Pagination-->
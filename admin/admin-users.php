<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();
?>





<?php
if (isset($_GET['edit'])) {
    $role = $_GET['role'];
    if ($role == 'Admin') {
        $role = 'Standard';
    } else {
        $role = 'Admin';
    }
    $user_id = $_GET['edit'];
    $stmtImages = $conn->prepare("UPDATE users SET user_role = ? WHERE user_id = ? ");
    $stmtImages->bind_param("si", $role, $user_id);
    $stmtImages->execute();
    $prevUrl = $_SESSION['prevUrl'];
    header("location: $prevUrl ");
    unset($_SESSION['prevUrl']);
}


?>


<body class="">
    <div class="wrapper">
        <div class="sidebar">
            <!--
            Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
             -->
            <?php

            include_once("includes/admin-sidebar.php");

            ?>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <?php
            include_once("includes/admin-navbar.php");

            ?>
            <!-- End Navbar -->
            <div class="content">
                <?php
                if (isset($_GET['delete'])) {
                    $user_id = $_GET['delete'];
                    $sql = "DELETE FROM users WHERE user_id = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();

                    $sql = "DELETE FROM comments WHERE comment_author = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $prevUrl = $_SESSION['prevUrl'];
                    header("location: $prevUrl ");
                    unset($_SESSION['prevUrl']);
                    echo '<div class="alert alert-success" role="alert">
Korisnik uspješno obrisan.
</div>';
                }


                ?>
                <div class="row">
                    <table class="table  table-striped table-bordered table-hover">
                        <thead>



                            <tr>
                                <th class="text-center">ID</th>
                                <th>Username</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>E-mail</th>
                                <th>Ovlast</th>
                                <th class="text-center">Promjena</th>
                                <th class="text-center">Slika</th>
                                <th class="text-center">Brisanje</th>
                            </tr>
                        </thead>
                        <tbody>



                            <?php


                            $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
                            $sql = $conn->prepare("SELECT * FROM users");
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
                            $sql = $conn->prepare("SELECT * FROM users LIMIT $paginationStart, $limit");
                            $sql->execute();
                            $resultsUsers = $sql->get_result();

                            //$user = $result->fetch_assoc(); // fetch data 
                            while ($row = mysqli_fetch_assoc($resultsUsers)) {

                                $user_id = $row['user_id'];
                                $username = $row["username"];
                                $user_firstname = $row["user_firstname"];
                                $user_lastname = $row["user_lastname"];
                                $user_email = $row["user_email"];
                                $user_image = $row["user_image"];
                                $user_role = $row["user_role"];

                            ?>

                                <tr>
                                    <td class="text-center"><?php echo $user_id ?></td>

                                    <td><?php echo $username ?></td>
                                    <td><?php echo $user_firstname ?></td>
                                    <td><?php echo $user_lastname ?></td>
                                    <td><?php echo $user_email ?></td>
                                    <td><?php echo $user_role ?></td>

                                    <?php
                                    if ($user_id == $_SESSION['user_id']) {
                                        $userAdmin = 'disabled';
                                    } else {
                                        $userAdmin = '';
                                    }
                                    ?>

                                    <td class="td-actions text-center">

                                        <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon <?php echo $userAdmin ?>" data-toggle="modal" data-target="#exampleModal3<?php echo $user_id ?>">
                                            <i class="tim-icons icon-settings"></i>
                                        </button>
                                    </td>
                                    <td class="td-actions text-center">

                                        <button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal2<?php echo $user_id ?>">
                                            <i class="tim-icons  icon-badge"></i>
                                        </button>
                                    </td>
                                    <td class="td-actions text-center">
                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon <?php echo $userAdmin ?>" data-toggle="modal" data-target="#exampleModal1<?php echo $user_id ?>">
                                            <i class="tim-icons icon-simple-remove"></i>
                                        </button>

                                    </td>
                                    <?php include("includes/modals-users.php") ?>
                                </tr>



                            <?php
                            }




                            ?>





                        </tbody>




                    </table>

                </div>
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
                                <a class="page-link" href="<?php echo 'admin-users.php?page=' . $i ?>"><?php echo $i ?>
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


            </div>

        </div>
    </div>




    <?php

    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

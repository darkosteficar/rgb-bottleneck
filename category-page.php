<?php


include_once("includes/header.php");
include("includes/db.php");
?>


<!-- Navbar -->
<?php
include("includes/navbar.php");
$manufacturer = $_GET['manufacturer'];
if ($manufacturer == 'AMD') {
    $color = 'danger';
    $colorHover = 'red';
} else if ($manufacturer == 'Intel') {
    $color = 'primary';
    $colorHover = 'blue';
} else {
    $color = 'success';
    $colorHover = 'green';
}
?>
<!-- Navbar -->

</header>
<!--Main Navigation-->


<!--Main layout-->
<main class="mt-5 ">
    <div class="container">



        <!--Section: Cards-->
        <section class="pt-5">


            <?php



            $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
            $sql = $conn->prepare("SELECT * FROM posts WHERE manufacturer = ?");
            $sql->bind_param('s', $manufacturer);
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
            $sql = $conn->prepare("SELECT * FROM posts WHERE manufacturer = ? LIMIT $paginationStart, $limit");
            $sql->bind_param('s', $manufacturer);
            $sql->execute();
            $resultsCat = $sql->get_result();



            ?>


            <div class="container text-center mr-2  bg-<?php echo $color ?>">
                <h2 class="text-white fw-bold shadow-lg" style="font-weight: bold;"><?php echo $_GET['manufacturer'] ?></h2>
            </div>

            <!--Grid row-->
            <div class="row mt-3 wow fadeIn">

                <?php
                while ($row = mysqli_fetch_assoc($resultsCat)) {

                ?>


                    <?php
                    $postIdd = $row['post_id'];
                    $query = $conn->prepare('SELECT * FROM images WHERE post_id = ? LIMIT 1');
                    $query->bind_param('s', $postIdd);
                    $query->execute();
                    $results1 = $query->get_result();
                    $row1 = mysqli_fetch_assoc($results1);


                    $cat_name = $row['post_category_id'];

                    $query = $conn->prepare('SELECT * FROM categories WHERE cat_id = ? LIMIT 1');
                    $query->bind_param('i', $cat_name);
                    $query->execute();
                    $results1 = $query->get_result();
                    $row2 = mysqli_fetch_assoc($results1)



                    ?>
                    <!--Grid column-->
                    <div class="col-lg-5 col-xl-4 mb-4">
                        <!--Featured image-->
                        <div class="view overlay rounded z-depth-1">
                            <img src="admin/images/<?php echo $row1['name'] ?>" class="img-fluid border rounded border-<?php echo $color ?>" alt="">
                            <a href="post-page.php?id=<?php echo $row['post_id'] ?>" target="_blank">
                                <div class="mask rgba-<?php echo $colorHover ?>-light"></div>
                            </a>
                        </div>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-lg-7 col-xl-7 ml-xl-4 mb-4 pt-2 border rounded border-white bg-<?php echo $color ?>">
                        <h3 class="mb-3 font-weight-bold dark-black-text">
                            <strong><?php echo $row['post_title'] ?></strong>
                        </h3>
                        <?php $contentTrimmed =  mb_strimwidth($row['post_content'], 0, 200, "..."); ?>
                        <p class="dark-black-text"><?php echo $contentTrimmed ?></p>
                        <span class="badge rounded-pill bg-<?php echo $color ?> fa-calendar-alt float-left "><i class="fas fa-calendar-alt mr-2"></i><?php echo $row['post_date']  ?></span>
                        <span class="badge bg-<?php echo $color ?> float-right"><?php echo $row2['cat_title']  ?></span>

                    </div>
                    <!--Grid column-->

                <?php



                }
                ?>


            </div>
            <!--Grid row-->

            <hr class="mb-5">



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
                                                        echo '?page=' . $prev . '&manufacturer=' . $manufacturer;
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
                            <a class="page-link" href="<?php echo 'category-page.php?manufacturer=' . $manufacturer . '&page=' . $i ?>"><?php echo $i ?>
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
                                                        echo '?page=' . $next . '&manufacturer=' . $manufacturer;
                                                    } ?> " aria-label="Next">
                            <span aria-hidden="true">&rArr;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!--Pagination-->

        </section>
        <!--Section: Cards-->

    </div>
</main>
<!--Main layout-->

<!--Footer-->
<?php include("includes/footer.php"); ?>
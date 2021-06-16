<?php


include_once("includes/header.php");
include("includes/db.php");
?>


<!-- Navbar -->
<?php
include("includes/navbar.php");

?>
<!-- Navbar -->

</header>
<!--Main Navigation-->

<!--Main layout-->
<main class="mt-5 pt-5">

    <div class="container">


        <div id="carouselExampleIndicators" class="carousel slide mb-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">

                <div class="carousel-item active border border-danger ">
                    <a href="category-page.php?manufacturer=AMD">
                        <img class="d-block w-100" src="img/AMD-Car.png" alt="First slide">
                    </a>

                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="text-danger fw-bold shadow-lg" style="font-weight: bold;">AMD</h2>
                        <h5 class="text-danger fw-bold shadow-lg" style="font-weight: bold;">Big Navi - ZEN 3 - Threadripper</h5>

                    </div>


                </div>

                <div class="carousel-item border border-success">
                    <a href="category-page.php?manufacturer=Nvidia">

                        <img class="d-block w-100" src="img/Nvidia-Car.jpg" alt="Second slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h2 class="text-success fw-bold shadow-lg" style="font-weight: bold;">Nvidia</h2>
                            <h5 class="text-success fw-bold shadow-lg" style="font-weight: bold;">RTX 3000</h5>

                        </div>
                    </a>
                </div>
                <div class="carousel-item border border-primary">
                    <a href="category-page.php?manufacturer=Intel">
                        <img class="d-block w-100" src="img/Intel-Car.png" alt="Third slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h2 class="text-primary fw-bold shadow-lg" style="font-weight: bold;">Intel</h2>
                            <h5 class="text-primary fw-bold shadow-lg" style="font-weight: bold;">11th Gen</h5>
                        </div>
                    </a>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>


        <!--Section: Cards-->

        <section class="text-center">

            <!--Grid row-->
            <div class="row mb-4 wow fadeIn">
                <!-- AMD
                <!--Grid column-->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h2 class="text-danger fw-bold shadow-lg" style="font-weight: bold;">AMD</h2>
                    <?php
                    $manu = 'AMD';
                    $query = $conn->prepare('SELECT * FROM posts WHERE manufacturer = ? ORDER BY post_id DESC LIMIT 5');
                    $query->bind_param('s', $manu);
                    $query->execute();
                    $results = $query->get_result();
                    while ($row = mysqli_fetch_assoc($results)) {

                    ?>
                        <div class="card text-white bg-danger mb-3">
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
                            <!--Card image-->
                            <span class="border border-danger">
                                <div class="view overlay">
                                    <span class="badge bg-danger float-right"><?php echo $row2['cat_title']  ?></span>
                                    <span class="badge bg-danger float-left"><?php echo $row2['cat_title']  ?></span>
                                    <img src="admin/images/<?php echo $row1['name'] ?>" class="card-img-top" alt="">
                                    <a href="post-page.php?id=<?php echo $postIdd ?>" target="_blank">
                                        <div class="mask rgba-red-light"></div>
                                    </a>
                                </div>
                            </span>
                            <!--Card content-->
                            <div class="card-body">
                                <!--Title-->
                                <div class="row" style="margin-left: 10px;">
                                    <h4 class="card-title mr-3"><?php echo $row['post_title'] ?></h4>
                                </div>

                                <!--Text-->

                                <?php $contentTrimmed =  mb_strimwidth($row['post_content'], 0, 200, "..."); ?>
                                <p class="card-text text-white"><?php echo $contentTrimmed ?></p>
                                <span class="badge rounded-pill bg-danger fa-calendar-alt float-right "><i class="fas fa-calendar-alt mr-2"></i><?php echo $row['post_date']  ?></span>

                            </div>

                        </div>

                    <?php
                    }




                    ?>


                </div>
                <!--Grid column-->

                <!-- Nvidia
                <!--Grid column-->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h2 class="text-success fw-bold shadow-lg" style="font-weight: bold;">Nvidia</h2>
                    <?php
                    $manu = 'Nvidia';
                    $query = $conn->prepare('SELECT * FROM posts WHERE manufacturer = ? ORDER BY post_id DESC LIMIT 5');
                    $query->bind_param('s', $manu);
                    $query->execute();
                    $results = $query->get_result();
                    while ($row = mysqli_fetch_assoc($results)) {

                    ?>
                        <div class="card text-white bg-success mb-3">
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
                            $row2 = mysqli_fetch_assoc($results1);


                            ?>
                            <!--Card image-->
                            <span class="border border-success">
                                <div class="view overlay">
                                    <span class="badge bg-success float-right"><?php echo $row2['cat_title']  ?></span>
                                    <span class="badge bg-success float-left"><?php echo $row2['cat_title']  ?></span>
                                    <img src="admin/images/<?php echo $row1['name'] ?>" class="card-img-top" alt="">
                                    <a href="post-page.php?id=<?php echo $postIdd ?>" target="_blank">
                                        <div class="mask rgba-green-light"></div>
                                    </a>
                                </div>
                            </span>
                            <!--Card content-->
                            <div class="card-body">
                                <!--Title-->
                                <h4 class="card-title"><?php echo $row['post_title'] ?></h4>
                                <!--Text-->
                                <?php $contentTrimmed =  mb_strimwidth($row['post_content'], 0, 200, "..."); ?>
                                <p class="card-text text-white"><?php echo $contentTrimmed ?></p>
                                <span class="badge rounded-pill bg-success fa-calendar-alt float-right "><i class="fas fa-calendar-alt mr-2"></i><?php echo $row['post_date']  ?></span>

                            </div>

                        </div>

                    <?php
                    }




                    ?>


                </div>
                <!--Grid column-->

                <!-- Intel
                <!--Grid column-->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h2 class="text-primary fw-bold shadow-lg" style="font-weight: bold;">Intel</h2>
                    <?php
                    $manu = 'Intel';
                    $query = $conn->prepare('SELECT * FROM posts WHERE manufacturer = ? ORDER BY post_id DESC LIMIT 5');
                    $query->bind_param('s', $manu);
                    $query->execute();
                    $results = $query->get_result();
                    while ($row = mysqli_fetch_assoc($results)) {

                    ?>
                        <div class="card text-white bg-primary mb-3">
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
                            $row2 = mysqli_fetch_assoc($results1);

                            ?>
                            <!--Card image-->
                            <span class="border border-primary">
                                <div class="view overlay">
                                    <span class="badge bg-primary float-right"><?php echo $row2['cat_title']  ?></span>
                                    <span class="badge bg-primary float-left"><?php echo $row2['cat_title']  ?></span>
                                    <img src="admin/images/<?php echo $row1['name'] ?>" class="card-img-top" alt="">
                                    <a href="post-page.php?id=<?php echo $postIdd ?>" target="_blank">
                                        <div class="mask rgba-blue-light"></div>
                                    </a>
                                </div>
                            </span>
                            <!--Card content-->
                            <div class="card-body">
                                <!--Title-->
                                <h4 class="card-title"><?php echo $row['post_title'] ?></h4>
                                <!--Text-->
                                <?php $contentTrimmed =  mb_strimwidth($row['post_content'], 0, 200, "..."); ?>
                                <p class="card-text text-white"><?php echo $contentTrimmed ?></p>

                                <span class="badge rounded-pill bg-primary fa-calendar-alt float-right "><i class="fas fa-calendar-alt mr-2"></i><?php echo $row['post_date']  ?></span>
                            </div>

                        </div>

                    <?php
                    }




                    ?>


                </div>
                <!--Grid column-->

            </div>
            <!--Grid row-->




            <?php



            $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
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
            $authors = $conn->query("SELECT * FROM posts LIMIT $paginationStart, $limit")->fetch_all();

            ?>


        </section>

        <!--Section: Cards-->

    </div>
</main>
<!--Main layout-->

<!--Footer-->
<?php

include_once("includes/footer.php");

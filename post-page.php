<?php


include_once("includes/header.php");
include("includes/db.php");
include("includes/functions.php");
?>


<!-- Navbar -->
<?php
include("includes/navbar.php");

?>
<!-- Navbar -->

<?php

if (isset($_GET['id'])) {

    $Getpost = "SELECT * FROM posts WHERE post_id = ?";
    $Getpost = $conn->prepare($Getpost);
    $Getpost->bind_param('i', $_GET['id']);
    $Getpost->execute();
    $GetpostResults = $Getpost->get_result();
    $row = mysqli_fetch_assoc($GetpostResults);
    $post_Author = $row['post_author'];
    $post_id = $row['post_id'];
    $post_manuf = $row['manufacturer'];
    if ($post_manuf == 'AMD') {
        $color = 'danger';
    } else if ($post_manuf == 'Nvidia') {
        $color = 'success';
    } else {
        $color = 'primary';
    }
}

?>

</header>
<!--Main Navigation-->

<!--Main layout-->
<main class="mt-5 pt-5">
    <div class="container">

        <!--Section: Post-->
        <section class="mt-4">

            <!--Grid row-->
            <div class="row">

                <!--Grid column-->
                <div class="col-md-8 mb-4">
                    <?php
                    $postIdd = $row['post_id'];
                    $query = $conn->prepare('SELECT * FROM images WHERE post_id = ? LIMIT 1');
                    $query->bind_param('s', $postIdd);
                    $query->execute();
                    $results1 = $query->get_result();
                    $row1 = mysqli_fetch_assoc($results1);

                    ?>
                    <!--Featured Image-->
                    <div class="card mb-4 wow fadeIn border border-<?php echo $color ?> rounded" ">

                        <img src=" admin/images/<?php echo $row1['name'] ?>" class="img-fluid" alt="">
                        <div class="border border-<?php echo $color ?> rounded" style="position: absolute;z-index:3;bottom:0;background-color:white;width:100%;height:50px">

                            <p style="font-size: 30px;font-weight:600" class=" text-<?php echo $color ?> mt-1 ml-2"><?php echo $row['post_title'] ?></p>
                        </div>
                    </div>
                    <!--/.Featured Image-->
                    <!--Card-->
                    <div class="card mb-4 wow fadeIn">
                        <!--Card content-->
                        <div class="card-body">
                            <p><?php echo $row['post_content'] ?></p>
                        </div>
                    </div>
                    <?php
                    $postIdd = $row['post_id'];
                    $query = $conn->prepare('SELECT * FROM images WHERE post_id = ?');
                    $query->bind_param('s', $postIdd);
                    $query->execute();
                    $results1 = $query->get_result();
                    while ($row1 = mysqli_fetch_assoc($results1)) {
                    ?>
                        <!--/.Card-->
                        <!--Featured Images-->
                        <div class="card mb-4 wow fadeIn">

                            <img src="admin/images/<?php echo $row1['name'] ?>" class="img-fluid" alt="">


                        </div>
                        <!--/.Featured Images-->
                        <!--Card-->
                    <?php
                    }


                    ?>

                    <div class="card mb-4 wow fadeIn">

                        <div class="card-header bg-<?php echo $color ?> text-white font-weight-bold">
                            <span>Autor</span>
                            <span class="pull-right">

                            </span>
                        </div>

                        <!--Card content-->
                        <div class="card-body">
                            <?php
                            $user_id = $post_Author;
                            $getPostAuthor = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
                            $getPostAuthor->bind_param('i', $user_id);
                            $getPostAuthor->execute();
                            $getPostAuthorName = $getPostAuthor->get_result();
                            if (mysqli_num_rows($getPostAuthorName) == 0) {
                                $userAuthorName = 'Nepoznat';
                            } else {
                                $userAuthor = mysqli_fetch_assoc($getPostAuthorName);
                                $userAuthorName = $userAuthor['username'];
                            }


                            ?>
                            <div class="media d-block d-md-flex mt-3">
                                <?php if (mysqli_num_rows($getPostAuthorName) != 0) {
                                ?>
                                    <img class="d-flex mb-3 mx-auto z-depth-1" src="userImages/<?php echo $userAuthor['user_image'] ?>" alt="Generic placeholder image" style="width: 100px;">
                                <?php } ?>
                                <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                    <h5 class="mt-0 font-weight-bold"><?php echo $userAuthorName ?>
                                    </h5>

                                </div>
                            </div>

                        </div>

                    </div>

                    <!--/.Card-->

                    <?php
                    if (isset($_SESSION['user_id'])) {
                    ?>
                        <!--Reply-->
                        <div class="card mb-3 wow fadeIn">
                            <div class="card-header bg-<?php echo $color ?> text-white font-weight-bold comment_header">Postavite komentar</div>
                            <div class="card-body">

                                <!-- Default form reply -->
                                <form method="post" id="commentForm">

                                    <!-- Comment -->
                                    <div class="form-group">
                                        <span id="message"></span>
                                        <label for="replyFormComment">Vaš komentar</label>
                                        <textarea name="comment_content" class="form-control" id="replyFormComment" rows="5"></textarea>
                                    </div>
                                    <input type="hidden" name="comment_author" value="<?php echo $_SESSION['user_id'] ?>">
                                    <input type="hidden" name="post_id" value="<?php echo $_GET['id'] ?>">



                                    <div class="text-center mt-4">
                                        <button onclick="showComments()" class="btn btn-<?php echo $color ?>  btn-md" name="submit" id="submit" type="submit">Objavi</button>
                                    </div>
                                </form>
                                <span id="comment_message"></span>
                                <!-- Default form reply -->



                            </div>
                        </div>
                        <!--/.Reply-->


                    <?php
                    }



                    ?>

                    <!--Comments-->
                    <div class="card card-comments  mb-3 wow fadeIn">
                        <div class="card-header bg-<?php echo $color ?> text-white font-weight-bold">Komentari</span></div>
                        <div class="card-body card-body-comments" id="card-body-comments231">
                            <?php

                            $sql = 'SELECT * FROM comments WHERE comment_post_id = ? ORDER BY comment_id DESC';
                            $sql = $conn->prepare($sql);
                            $sql->bind_param('i', $_GET['id']);
                            $sql->execute();
                            $results = $sql->get_result();

                            while ($row = mysqli_fetch_assoc($results)) {
                                $sql1 = 'SELECT * FROM users WHERE user_id = ?';
                                $sql1 = $conn->prepare($sql1);

                                $sql1->bind_param('i', $row['comment_author']);
                                $sql1->execute();
                                $results1 = $sql1->get_result();
                                $userName = mysqli_fetch_assoc($results1);
                            ?>



                                <div class="media d-block d-md-flex mt-3 border-top border-black rounded p-3">
                                    <img class="d-flex mb-3 mx-auto " src="userImages/<?php echo $userName['user_image'] ?>" alt="Generic placeholder image">
                                    <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                        <h5 class="mt-0 font-weight-bold"><?php echo $userName['username'] ?>

                                            <a href="" class="pull-right text-dark " style="font-size: 15px;">
                                                <?php echo $timeAgo = $row['comment_date'] ?>
                                            </a>
                                        </h5>
                                        <div class="mw-80" style="width: 600px;">
                                            <?php
                                            echo $row['comment_content'];


                                            ?>


                                        </div>
                                    </div>
                                </div>



                            <?php
                            }


                            ?>
                        </div>

                    </div>
                    <!--/.Comments-->


                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-md-4 mb-4">

                    <!--Card: Jumbotron-->
                    <div class="card blue-gradient mb-4 wow fadeIn">

                        <!-- Content -->
                        <div class="card-body text-white text-center">

                            <h4 class="mb-4">
                                <strong>RGB Bottleneck</strong>
                            </h4>
                            <p>
                                <strong>Najnovije vijesti iz svijeta komponenti</strong>
                            </p>
                            <p class="mb-4">
                                <strong>Iz dana u dan trudimo se poboljšati platformu kako bi naši korisnici imali
                                    što bolje korisničko iskustvo.
                                </strong>
                            </p>


                        </div>
                        <!-- Content -->
                    </div>
                    <!--Card: Jumbotron-->



                    <!--Card-->
                    <div class="card mb-4 wow fadeIn">

                        <div class="card-header bg-<?php echo $color ?> text-white">Predloženo</div>

                        <!--Card content-->
                        <div class="card-body">

                            <ul class="list-unstyled">
                                <?php
                                $query = "SELECT * FROM posts WHERE post_id NOT IN(SELECT post_id FROM posts WHERE post_id = ? ) AND post_id IN(SELECT post_id FROM posts WHERE manufacturer = ?) LIMIT 5";
                                $relatedPosts = $conn->prepare($query);
                                $relatedPosts->bind_param("is", $post_id, $post_manuf);
                                $relatedPosts->execute();
                                $resultsOfRelated = $relatedPosts->get_result();

                                while ($relatePost = mysqli_fetch_assoc($resultsOfRelated)) {
                                ?>
                                    <?php $titleTrimmed =  mb_strimwidth($relatePost['post_title'], 0, 40, "..."); ?>
                                    <li class="media my-4 shadow-2-strong border-bottom pb-3">
                                        <img width="50" height="50" class="d-flex mr-3" src="img/<?php echo $post_manuf ?>.jpg " alt="An image">
                                        <div class="media-body">
                                            <a class="text-<?php echo $color ?>" href="post-page.php?id=<?php echo $relatePost['post_id'] ?>">
                                                <h5 class="mt-0 mb-1 font-weight-bold"><?php echo $titleTrimmed ?></h5>
                                            </a>
                                            <?php $contentTrimmed =  mb_strimwidth($relatePost['post_content'], 0, 100, "..."); ?>
                                            <?php echo $contentTrimmed ?>
                                        </div>
                                    </li>

                                <?php
                                }
                                ?>



                            </ul>

                        </div>

                    </div>
                    <!--/.Card-->

                </div>
                <!--Grid column-->

            </div>
            <!--Grid row-->

        </section>
        <!--Section: Post-->

        <form method="post" id="getPostId">
            <input type="hidden" value="<?php echo $_GET['id'] ?>">
        </form>

    </div>
</main>
<!--Main layout-->


<!--Footer-->
<?php

include_once("includes/footer.php");
?>
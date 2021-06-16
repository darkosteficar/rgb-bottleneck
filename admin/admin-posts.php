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

                if (isset($_SESSION['success'])) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['success'] ?>
                    </div>
                <?php
                    unset($_SESSION['success']);
                }



                if (isset($_GET['source'])) {

                    $source = $_GET['source'];
                } else {
                    $source = '';
                }

                switch ($source) {

                    case 'add_post';
                        include "includes/add_post.php";
                        break;
                    case 'edit_post';
                        include "includes/edit_post.php";
                        break;


                    default:
                        include "includes/view_all_posts.php";
                        break;
                }


                ?>
            </div>

        </div>
    </div>



    <?php

    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

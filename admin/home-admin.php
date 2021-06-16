<?php

include_once("includes/admin-header.php");
include_once("includes/db.php");

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
                include_once("includes/admin-graphs.php");

                ?>

            </div>

        </div>
    </div>



    <?php


    include_once("includes/admin-footer.php");

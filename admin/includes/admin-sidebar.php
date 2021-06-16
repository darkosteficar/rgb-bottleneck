<?php
$url = $_SERVER['REQUEST_URI'];

//strpos($a, 'are');


?>


<div class="sidebar-wrapper ">
    <div class="logo">

        <a href="../home-page.php" class="simple-text logo-normal ml-3">
            HOME
        </a>
    </div>
    <ul class="nav">
        <li <?php if (strpos($url, 'home-admin')) {
                echo 'class="active"';
            } ?>>
            <a href="home-admin.php">
                <i class="tim-icons icon-chart-pie-36"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'admin-posts.php')) {
                echo 'class="active"';
            } ?>">
            <a href="admin-posts.php">
                <i class="tim-icons icon-components"></i>
                <p>Objave</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'admin-posts.php?source')) {
                echo 'class="active"';
            } ?>>
            <a href="admin-posts.php?source=<?php echo 'add_post' ?>">
                <i class="tim-icons icon-simple-add"></i>
                <p>Nova objava</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'admin-categories')) {
                echo 'class="active"';
            } ?>>
            <a href="admin-categories.php">
                <i class="tim-icons icon-bullet-list-67"></i>
                <p>Kategorije</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'admin-users')) {
                echo 'class="active"';
            } ?>>
            <a href="admin-users.php">
                <i class="tim-icons icon-single-02"></i>
                <p>Korisnici</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'admin-comments')) {
                echo 'class="active"';
            } ?>>
            <a href="admin-comments.php">
                <i class="tim-icons icon-single-02"></i>
                <p>Komentari</p>
            </a>
        </li>


    </ul>
</div>
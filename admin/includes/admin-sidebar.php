<?php
$url = $_SERVER['REQUEST_URI'];

//strpos($a, 'are');


?>


<div class="sidebar-wrapper ">
    <div class="logo">

        <a href="../index.php" class="simple-text logo-normal ml-3">
            HOME
        </a>
    </div>
    <ul class="nav">

        <li <?php if (strpos($url, 'products')) {
                echo 'class="active"';
            } ?>>
            <a href="items.php?search=">
                <i class="tim-icons icon-tv-2"></i>
                <p>Proizvodi</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'add-product')) {
                echo 'class="active"';
            } ?>>
            <a href="add-item.php">
                <i class="tim-icons icon-tv-2"></i>
                <p>Novi proizvod</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'buyers')) {
                echo 'class="active"';
            } ?>>
            <a href="buyers.php?search=">
                <i class="tim-icons icon-single-02"></i>
                <p>Kupci</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'orders')) {
                echo 'class="active"';
            } ?>>
            <a href="orders.php?search=">
                <i class="tim-icons icon-single-02"></i>
                <p>Narudžbe</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'attributes')) {
                echo 'class="active"';
            } ?>>
            <a href="attributes.php?search=">
                <i class="tim-icons icon-single-02"></i>
                <p>Atributi</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'brands.php')) {
                echo 'class="active"';
            } ?>">
            <a href="brands.php?search=">
                <i class="tim-icons icon-components"></i>
                <p>Brandovi</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'categories.php')) {
                echo 'class="active"';
            } ?>">
            <a href="categories.php?search=">
                <i class="tim-icons icon-components"></i>
                <p>Kategorije</p>
            </a>
        </li>
        <li <?php if (strpos($url, 'statuses.php')) {
                echo 'class="active"';
            } ?>">
            <a href="statuses.php?search=">
                <i class="tim-icons icon-components"></i>
                <p>Statusi</p>
            </a>
        </li>



    </ul>
</div>
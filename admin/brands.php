<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();
$searchKey = '';

if (!empty($_GET['search'])) {
    $searchKey = $_GET['search'];
}

$main = 'brands';
?>

<body class="">
    <div class="wrapper">
        <div class="sidebar">
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
                if (isset($_POST['create'])) {
                    if (createBrand()) {
                        $_SESSION['success'] = 'Brand je uspješno dodan!';
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Brend nije dodan.</div>';
                    }
                }
                if (isset($_POST['edit'])) {
                    if (isset($_GET['id'])) {
                        updateBrand();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Nije odabran brend za promjenu.</div>';
                    }
                }
                if (isset($_GET['delete'])) {
                    deleteBrand();
                }

                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success" role="alert">' .  $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <form action="" method="post" enctype="multipart/form-data" name="add_category">
                                    <div class="form-group">
                                        <label for="category">Novi brand</label>
                                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime branda.
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Opis branda</label>
                                            <textarea name="description" class="form-control" id="post_content" rows="7" "></textarea>

                                        </div>
                                    </div>
                                    <button type=" submit" name="create" class="btn btn-primary">Dodaj</button>
                                </form>

                            </div>
                        </div>
                        <?php
                        if (isset($_GET['id'])) {
                            $brand = editBrand();
                            include("includes/admin-brands-editForm.php");
                        } ?>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h4 class="card-title"> Brendovi</h4>
                            </div>
                            <div class="card-body">
                                <form action="brands.php" method="get" enctype="multipart/form-data">
                                    <?php include("includes/admin-search-simple.php"); ?>
                                </form>
                                <div class="table-responsive">
                                    <table class="table tablesorter " id="">
                                        <thead class=" text-primary">
                                            <tr>
                                                <th>
                                                    ID
                                                </th>
                                                <th>
                                                    Ime branda
                                                </th>
                                                <th>
                                                    Opis branda
                                                </th>
                                                <th class="text-center">Promjena</th>
                                                <th class="text-center">Brisanje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            list($resultsBrands, $limit, $page, $prev, $next, $totoalPages) = getAll($main);
                                            while ($row = mysqli_fetch_assoc($resultsBrands)) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['id'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['name'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['description'] ?>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <a href="brands.php?id=<?php echo $row['id'];
                                                                                if (isset($_GET['page'])) echo "&page=" . $_GET['page']; ?>"><button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon">
                                                                <i class="tim-icons icon-settings"></i>
                                                            </button></a>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon " data-toggle="modal" data-target="#exampleModal1<?php echo $row['id'] ?>">
                                                            <i class="tim-icons icon-simple-remove"></i>
                                                        </button>
                                                    </td>
                                                    <?php include("includes/modals-brands.php") ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <!-- Pagination -->
                                    <?php pagination($main); ?>
                                    <!-- End Pagination -->
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

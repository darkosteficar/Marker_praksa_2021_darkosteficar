<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();

if (isset($_POST['search'])) {
    $searchKey = $_POST['key'];
    $searchField = $_POST['field'];
} else if (isset($_GET['search']) && isset($_GET['field'])) {
    $searchKey = $_GET['search'];
    $searchField = $_GET['field'];
}

$table = 'buyers';
$main = 'buyers';
?>
<?php
if (isset($_POST['edit'])) {
    updateBuyer();
}
?>

<body class="">
    <div class="wrapper">
        <div class="sidebar">
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
                    deleteBuyer();
                }
                ?>
                <div class="row">
                    <table class="table  table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>E-mail</th>
                                <th>Adresa</th>
                                <th class="text-center">Promjena</th>
                                <th class="text-center">Brisanje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_SESSION['success'])) {
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                                unset($_SESSION['success']);
                            }
                            ?>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row align-items-center justify-content-start">
                                        <form action="buyers.php" method="get" enctype="multipart/form-data">
                                            <div class="col-sm-3">
                                                <input type="text" name="search" class="form-control" placeholder="Pretraga">
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-success btn-md" type="submit">Pretraži</button>
                                            </div>
                                            <div class="col-sm-3 mr-5">
                                                <div class="form-group ">
                                                    <label for="inputState">Polja</label>
                                                    <select id="inputState" class="form-control" style="background-color: black;" name="field">
                                                        <option value="name">Ime</option>
                                                        <option value="surname">Prezime</option>
                                                        <option value="email">Email</option>
                                                        <option value="address">Adresa</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="col-sm-2 ml-5">
                                            <a href="add-buyer.php"><button class="btn btn-success btn-md" type="submit" name="search">Dodaj kupca</button></a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                list($resultsBuyers, $limit, $page, $prev, $next, $totoalPages) = getAllWithField($table);
                                while ($row = mysqli_fetch_assoc($resultsBuyers)) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $row['id']  ?></td>
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row['surname'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['address'] ?></td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal3<?php echo $row['id'] ?>">
                                                <i class="tim-icons icon-settings"></i>
                                            </button>
                                        </td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon <?php echo $userAdmin ?>" data-toggle="modal" data-target="#exampleModal1<?php echo $row['id'] ?>">
                                                <i class="tim-icons icon-simple-remove"></i>
                                            </button>
                                        </td>
                                        <?php include("includes/modals-buyers.php") ?>
                                    </tr>
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <?php pagination($main); ?>
                <!-- End Pagination -->
            </div>

        </div>
    </div>
    <?php

    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

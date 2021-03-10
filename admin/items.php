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

$main = 'items';

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
                    $item = $_GET['delete'];
                    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
                    $stmt->bind_param("i", $item);
                    if ($stmt->execute()) {
                        $_SESSION['success'] = 'Proizvod je uspješno izbrisan!';
                        header("location: items.php");
                        exit();
                    } else {
                        echo '<div class="alert alert-danger" role="alert"> Dogodila se pogreška. </div>';
                        echo $conn->error;
                    }
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success" role="alert">' .  $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <div class="row">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Ime</th>
                                <th>Brand</th>
                                <th>Osnovna cijena</th>
                                <th>Popust</th>
                                <th>Na stanju</th>
                                <th>Opis</th>
                                <th>Opskrba</th>
                                <th>Izdvojeno</th>
                                <th>Aktivno</th>
                                <th class="text-center">Promjena</th>
                                <th class="text-center">Slike</th>
                                <th class="text-center">Brisanje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="items.php" method="get" enctype="multipart/form-data">
                                <div class="container">
                                    <div class="row align-items-center justify-content-start">
                                        <div class="col-sm-3">
                                            <input type="text" name="search" class="form-control" placeholder="Pretraga">
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-success btn-md" type="submit">Pretraži</button>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group ">
                                                <label for="inputState">Polja</label>
                                                <select id="inputState" class="form-control" style="background-color: black;" name="field">
                                                    <option value="name">Ime</option>
                                                    <option value="surname">Brand</option>
                                                    <option value="highlighted">Izdvojeno</option>
                                                    <option value="active">Aktivno</option>
                                                    <option value="allow_resupply">Opskrba</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                            list($resultsOrders, $limit, $page, $prev, $next, $totoalPages) = getAllItems();
                            //$user = $result->fetch_assoc(); // fetch data 
                            while ($row = mysqli_fetch_assoc($resultsOrders)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id'] ?></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo $row['brand'] ?></td>
                                    <td><?php echo $row['base_price'] ?></td>
                                    <td><?php echo $row['discount'] ?></td>
                                    <td><?php echo $row['avaliable_stock'] ?></td>
                                    <td><?php echo $row['description'] ?></td>
                                    <td><?php echo $row['allow_resupply'] ?></td>
                                    <td><?php echo $row['highlighted'] ?></td>
                                    <td><?php echo $row['active'] ?></td>
                                    <td class="td-actions text-center">
                                        <a href="edit-item.php?edit=<?php echo $row['id'] ?>">
                                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon" ">
                                            <i class=" tim-icons icon-settings"></i>
                                            </button>
                                        </a>
                                    </td>
                                    <td class="td-actions text-center">
                                        <a href="item-images.php?item=<?php echo $row['id'] ?>">
                                            <button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon">
                                                <i class="tim-icons  icon-badge"></i>
                                            </button>
                                        </a>
                                    </td>
                                    <td class="td-actions text-center">
                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon " data-toggle="modal" data-target="#exampleModal1<?php echo $row['id'] ?>">
                                            <i class="tim-icons icon-simple-remove"></i>
                                        </button>
                                    </td>
                                    <?php include("includes/modals-items.php") ?>
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

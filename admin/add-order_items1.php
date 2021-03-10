<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();


if (!isset($_GET['order'])) {
    header("location:orders.php");
}
$main = 'add-order_items.php';


if (isset($_POST['add'])) {
    $items = array();
    $items = $_POST['items'];
    $order = test_input($_POST['order']);
    foreach ($items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (item_id,order_id) VALUES (?,?)");
        $stmt->bind_param('ii', $item, $order);
        if (!$stmt->execute()) {
            echo $conn->error;
        };
    }
}



?>


<?php
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

                <h3>Popis proizvoda</h3>
                <div class="row">
                    <table class="table  table-striped table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Ime Proizvoda</th>
                                <th>Cijena</th>
                                <th class="text-center">Odabir</th>
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
                                        <form action="buyers.php" method="post" enctype="multipart/form-data">
                                            <div class="col-sm-3">
                                                <input type="text" name="key" class="form-control" placeholder="Pretraga">
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-success btn-md" type="submit" name="search">Pretraži</button>
                                            </div>
                                            <div class="col-sm-3">
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


                                    </div>
                                </div>


                                <?php
                                list($resultsOrderItems, $limit, $page, $prev, $next, $totoalPages) = getAllNotInOrder();
                                ?>
                                <form action="add-order_items.php" method="post" enctype="multipart/form-data">
                                    <?php
                                    while ($row = mysqli_fetch_assoc($resultsOrderItems)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php echo $row['base_price'] ?></td>

                                            <?php
                                            $userAdmin = '';
                                            ?>
                                            <input type="hidden" value="<?php echo $_GET['order'] ?>" name="order">
                                            <td class="td-actions text-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" style="font-size: 15px;">
                                                        <input class="form-check-input" name="items[]" type="checkbox" value="<?php echo $row['id'] ?>">

                                                        <span class="form-check-sign">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <div class="col-sm-2">
                                        <button class="btn btn-success btn-md" type="submit" name="add">Dodaj proizvode</button>
                                    </div>
                                </form>

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

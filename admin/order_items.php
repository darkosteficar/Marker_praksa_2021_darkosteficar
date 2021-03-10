<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();


if (!isset($_GET['order'])) {
    header("location:orders.php");
}


?>


<?php
if (isset($_POST['edit'])) {
    if (empty(!$_POST['price']) && empty(!$_POST['quantity'])) {
        $id = $_POST['id'];
        $order_id = $_POST['order_id'];
        $stmtImages = $conn->prepare("UPDATE order_items SET price = ?,quantity = ? WHERE item_id = ? AND order_id = ?");
        $stmtImages->bind_param("diii", $_POST['price'], $_POST['quantity'], $id, $order_id);
        if ($stmtImages->execute()) {
            $_SESSION['success'] = 'Proizvod je uspješno promjenjen!';
            header("location: order_items.php?order=" . $order_id);
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Dogodila se pogreška.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Sva polja za promjenu nisu popunjena.</div>';
    }
}
$sessionRole = 'admin';
if ($sessionRole != 'admin') {
    header('location: brands.php');
}

if (isset($_GET['delete'])) {
    $order = $_GET['order'];
    $item = $_GET['delete'];
    $sql = "DELETE FROM order_items WHERE order_id = ? AND item_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order, $item);
    $stmt->execute();
    $_SESSION['success'] = 'Proizvod je uspješno izbrisan!';
    $prevUrl = $_SESSION['prevUrl'];
    header("location: $prevUrl ");
    unset($_SESSION['prevUrl']);
}
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

                <h3>Popis proizvoda iz ove narudžbe</h3>
                <div class="row">
                    <table class="table  table-striped table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Ime Proizvoda</th>
                                <th>Cijena</th>
                                <th>Količina</th>
                                <th>Slika</th>
                                <th class="text-center">Uredi</th>
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
                                        <div class="col-sm-2 ml-5">
                                            <a href="add-order_items.php?order=<?php echo $_GET['order'] ?>"><button class="btn btn-success btn-md" type="submit" name="search">Dodaj proizvode</button></a>
                                        </div>

                                    </div>
                                </div>


                                <?php
                                $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
                                if (isset($searchKey)) {
                                    $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
                                    $field =   mysqli_real_escape_string($conn, $searchField);
                                    $sql = "SELECT * FROM buyers WHERE $field LIKE ?"; // SQL with parameters
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('s', $search);
                                } else {
                                    $sql = "SELECT order_items.quantity,order_items.price,items.name FROM order_items LEFT JOIN items ON items.id = order_items.item_id WHERE order_items.order_id = ?"; // SQL with parameters
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('i', $_GET['order']);
                                }
                                $stmt->execute();
                                $result = $stmt->get_result(); // get the mysqli result
                                $allRecrods = mysqli_num_rows($result);
                                // Calculate total pages
                                $totoalPages = ceil($allRecrods / $limit);
                                // Current pagination page number
                                $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
                                $prev = $page - 1;
                                $next = $page + 1;
                                // Offset
                                $paginationStart = ($page - 1) * $limit;

                                if (isset($searchKey)) {
                                    $sql = $conn->prepare("SELECT * FROM buyers WHERE name LIKE '?' LIMIT $paginationStart, $limit");
                                    $stmt->bind_param('s', $search);
                                    $sql->execute();
                                    $resultsOrderItems = $sql->get_result();
                                    echo $search;
                                } else {
                                    $sql = "SELECT order_items.quantity,order_items.price,items.name,order_items.item_id FROM order_items LEFT JOIN items ON items.id = order_items.item_id WHERE order_items.order_id = ? LIMIT $paginationStart, $limit"; // SQL with parameters
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('i', $_GET['order']);
                                    $stmt->execute();
                                    $resultsOrderItems = $stmt->get_result();
                                }
                                while ($row = mysqli_fetch_assoc($resultsOrderItems)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row['price'] ?></td>
                                        <td><?php echo $row['quantity'] ?></td>
                                        <td><?php echo $row['name'] ?></td>
                                        <?php
                                        $userAdmin = '';
                                        ?>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon <?php echo $userAdmin ?>" data-toggle="modal" data-target="#exampleModal3<?php echo $row['item_id'] ?>">
                                                <i class="tim-icons icon-settings"></i>
                                            </button>
                                        </td>
                                        <td class="td-actions text-center">
                                            <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon <?php echo $userAdmin ?>" data-toggle="modal" data-target="#exampleModal1<?php echo $row['item_id'] ?>">
                                                <i class="tim-icons icon-simple-remove"></i>
                                            </button>
                                        </td>
                                        <?php include("includes/modals-order_items.php") ?>
                                    </tr>
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
                <!--Pagination-->
                <nav class="d-flex justify-content-center wow fadeIn">
                    <ul class="pagination pg-blue">

                        <!--Arrow left-->
                        <li class="page-item <?php if ($page <= 1) {
                                                    echo 'disabled';
                                                } ?> ">
                            <a class="page-link" href="<?php if ($page <= 1) {
                                                            echo '#';
                                                        } else {
                                                            echo '?page=' . $prev;
                                                        } ?> " aria-label="Previous">
                                <span aria-hidden="true"> &lArr;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php
                        for ($i = 1; $i <= $totoalPages; $i++) {
                        ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    }  ?>">
                                <a class="page-link" href="<?php echo 'admin-users.php?page=' . $i ?>"><?php echo $i ?>
                                    <?php if ($page == $i) {
                                    ?>
                                        <span class="sr-only">(current)</span>
                                    <?php
                                    } ?>

                                </a>
                            </li>

                        <?php
                        }
                        ?>
                        <li class="page-item <?php if ($page >= $totoalPages) {
                                                    echo 'disabled';
                                                } ?> ">
                            <a class="page-link" href="<?php if ($page >= $totoalPages) {
                                                            echo '#';
                                                        } else {
                                                            echo '?page=' . $next;
                                                        } ?> " aria-label="Next">
                                <span aria-hidden="true">&rArr;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!--Pagination-->
            </div>

        </div>
    </div>
    <?php
    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();

if (isset($_POST['search'])) {
    $searchKey = $_POST['key'];
} else if (isset($_GET['search'])) {
    $searchKey = $_GET['search'];
}

?>
<?php
if (isset($_GET['edit'])) {
    $role = $_GET['role'];
    if ($role == 'Admin') {
        $role = 'Standard';
    } else {
        $role = 'Admin';
    }
    $user_id = $_GET['edit'];
    $stmtImages = $conn->prepare("UPDATE users SET user_role = ? WHERE user_id = ? ");
    $stmtImages->bind_param("si", $role, $user_id);
    $stmtImages->execute();
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
                <?php
                if (isset($_GET['delete'])) {
                    $user_id = $_GET['delete'];
                    $sql = "DELETE FROM users WHERE user_id = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();

                    $sql = "DELETE FROM comments WHERE comment_author = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $prevUrl = $_SESSION['prevUrl'];
                    header("location: $prevUrl ");
                    unset($_SESSION['prevUrl']);
                    echo '<div class="alert alert-success" role="alert">Korisnik uspješno obrisan.</div>';
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
                            <form action="products.php" method="post" enctype="multipart/form-data">
                                <div class="container">
                                    <div class="row align-items-center justify-content-start">
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
                                                    <option value="surname">Brand</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                            $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
                            if (isset($searchKey)) {
                                $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
                                $sql = "SELECT * FROM orders WHERE name LIKE ?"; // SQL with parameters
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param('s', $search);
                            } else {
                                $sql = "SELECT items.name,items.id,items.base_price,brands.name FROM items INNER JOIN brands ON brands.id = items.brand_id";
                                $stmt = $conn->prepare($sql);
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
                                $sql = $conn->prepare("SELECT * FROM orders WHERE name LIKE ? LIMIT $paginationStart, $limit");
                                $sql->bind_param("s", $search);
                                $sql->execute();
                                $resultsOrders = $sql->get_result();
                            } else {
                                $sql = $conn->prepare("SELECT items.name,items.discount,items.active,items.highlighted,items.id,items.base_price,items.description,items.avaliable_stock,items.allow_resupply,brands.name AS brand FROM items INNER JOIN brands ON brands.id = items.brand_id LIMIT $paginationStart, $limit");
                                $sql->execute();
                                $resultsOrders = $sql->get_result();
                            }

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
                                        <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon" data-toggle="modal" data-target="#exampleModal3<?php echo $row['id'] ?>">
                                            <i class="tim-icons icon-settings"></i>
                                        </button>
                                    </td>
                                    <td class="td-actions text-center">
                                        <a href="order_items.php?order=<?php echo $row['id'] ?>">
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
                                    <?php include("includes/modals-users.php") ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
                <!-- Pagination -->
                <?php if (isset($search)) { ?>
                    <!--Pagination with search-->
                    <nav class="d-flex justify-content-center wow fadeIn">
                        <ul class="pagination pg-blue">
                            <!--Arrow left-->
                            <li class="page-item <?php if ($page <= 1) {
                                                        echo 'disabled';
                                                    } ?> ">
                                <a class="page-link" href="<?php if ($page <= 1) {
                                                                echo '#';
                                                            } else {
                                                                echo '?page=' . $prev . '&search=' . $searchKey;
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
                                    <a class="page-link" href="<?php echo 'statuses.php?page=' . $i . '&search=' . $searchKey ?>"><?php echo $i ?>
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
                                                                echo '?page=' . $next . '&search=' . $searchKey;
                                                            } ?> " aria-label="Next">
                                    <span aria-hidden="true">&rArr;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!--Pagination with search-->
                <?php
                } else { ?>
                    <!--Pagination without search-->
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
                                    <a class="page-link" href="<?php echo 'statuses.php?page=' . $i ?>"><?php echo $i ?>
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
                    <!--Pagination without search-->
                <?php } ?>
                <!-- End Pagination -->
            </div>
        </div>
    </div>
    <?php
    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

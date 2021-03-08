<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();

$searchKey = searchKeyNoField();

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
                    if (!createAttribute()) {
                        echo '<div class="alert alert-danger" role="alert">
                        Dogodila se greška.
                        </div>';
                    }
                }
                if (isset($_POST['edit'])) {
                    if (isset($_GET['id'])) {
                        $name = $_POST['name'];
                        $value = $_POST['value'];
                        $id = $_GET['id'];
                        $stmtImages = $conn->prepare("UPDATE attributes SET name = ?,value = ? WHERE id = ? ");
                        $stmtImages->bind_param("ssi", $name, $value, $id);
                        $stmtImages->execute();
                        $_SESSION['success'] = 'Atribut ' . $name . ' je uspješno promjenjen!';
                        header("location: attributes.php");
                        exit();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Nije odabrana kategorija za promjenu.</div>';
                    }
                }
                if (isset($_GET['delete'])) {
                    $stmt = $conn->prepare("DELETE FROM attributes WHERE id = ?");
                    $stmt->bind_param('i', $_GET['delete']);
                    if ($stmt->execute()) {
                        $_SESSION['success'] = 'Atribut je uspješno izbrisan!';
                        header("location: attributes.php");
                        exit();
                    } else {
                        echo '<div class="alert alert-danger" role="alert"> Dogodila se greška. </div>';
                    }
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

                                <form action="" method="post" enctype="multipart/form-data" name="add_category" class="needs-validation" novalidate>
                                    <div class="form-group">
                                        <label for="category">Novi atribut</label>
                                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime atributa" required>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime atributa.
                                        </div>
                                        <input type="text" name="value" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Vrijednost atributa" required>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite vrijednost atributa.
                                        </div>
                                    </div>
                                    <button type="submit" name="create" class="btn btn-primary">Dodaj</button>
                                </form>

                            </div>
                        </div>
                        <?php
                        if (isset($_GET['id'])) {
                            $sql = "SELECT * FROM attributes WHERE id = ? LIMIT 1"; // SQL with parameters
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result(); // get the mysqli result
                            $nums = mysqli_num_rows($result);
                            //$user = $result->fetch_assoc(); // fetch data
                            $attribute = mysqli_fetch_assoc($result);
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="post" enctype="multipart/form-data" name="edit" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="category">Promjena atributa</label>
                                            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required value="<?php echo $attribute['name'] ?>">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite ime atributa.
                                            </div>
                                            <input type="text" name="value" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required value="<?php echo $attribute['value'] ?>">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite vrijednost atributa.
                                            </div>

                                        </div>
                                        <button type="submit" name="edit" class="btn btn-primary">Spremi</button>
                                    </form>
                                </div>
                            </div>
                        <?php
                        } ?>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h4 class="card-title"> Atributi</h4>
                            </div>
                            <div class="card-body">
                                <form action="attributes.php" method="post" enctype="multipart/form-data">
                                    <div class="container">
                                        <div class="row align-items-center">

                                            <div class="col-sm">
                                                <label for="">Pretraga</label>
                                                <input type="text" name="key" class="form-control">
                                            </div>
                                            <div class="col-sm">
                                                <button class="btn btn-success btn-md" type="submit" name="search">Pretraži</button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table tablesorter " id="">
                                        <thead class=" text-primary">
                                            <tr>
                                                <th>
                                                    ID
                                                </th>
                                                <th>
                                                    Ime atributa
                                                </th>
                                                <th>
                                                    Vrijednost atributa
                                                </th>
                                                <th class="text-center">Promjena</th>
                                                <th class="text-center">Brisanje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 3;
                                            if (isset($searchKey)) {
                                                $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
                                                $sql = "SELECT * FROM attributes WHERE name LIKE ?"; // SQL with parameters
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param('s', $search);
                                            } else {
                                                $sql = "SELECT * FROM attributes"; // SQL with parameters
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
                                                $sql = $conn->prepare("SELECT * FROM attributes WHERE name LIKE ? LIMIT $paginationStart, $limit");
                                                $sql->bind_param("s", $search);
                                                $sql->execute();
                                                $resultsAttributes = $sql->get_result();
                                            } else {
                                                $sql = $conn->prepare("SELECT * FROM attributes LIMIT $paginationStart, $limit");
                                                $sql->execute();
                                                $resultsAttributes = $sql->get_result();
                                            }




                                            //$user = $result->fetch_assoc(); // fetch data
                                            while ($row = mysqli_fetch_assoc($resultsAttributes)) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['id'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['name'] ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['value'] ?>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <a href="attributes.php?id=<?php echo $row['id'] ?>"><button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon">
                                                                <i class="tim-icons icon-settings"></i>
                                                            </button></a>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon " data-toggle="modal" data-target="#exampleModal1<?php echo $row['id'] ?>">
                                                            <i class="tim-icons icon-simple-remove"></i>
                                                        </button>
                                                    </td>
                                                    <?php include("includes/modals-attributes.php") ?>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
                                                        <a class="page-link" href="<?php echo 'attributes.php?page=' . $i . '&search=' . $searchKey ?>"><?php echo $i ?>
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
                                                        <a class="page-link" href="<?php echo 'attributes.php?page=' . $i ?>"><?php echo $i ?>
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
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php

    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

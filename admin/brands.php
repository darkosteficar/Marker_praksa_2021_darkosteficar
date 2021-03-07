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

<body class="">
    <div class="wrapper">
        <div class="sidebar">
            <!--
            Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
             -->
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
                        echo '<div class="alert alert-danger" role="alert">
                        Dogodila se greška.
                        </div>';
                    }
                }
                if (isset($_POST['edit'])) {
                    if (isset($_GET['id'])) {
                        $name = $_POST['name'];
                        $description = $_POST['description'];
                        $id = $_GET['id'];
                        $stmt = $conn->prepare("UPDATE brands SET name = ?,description = ? WHERE id = ? ");
                        $stmt->bind_param("ssi", $name, $description, $id);
                        $stmt->execute();
                        $_SESSION['success'] = 'Brand ' . $name . ' je uspješno promjenjen!';
                        header("location:" . $_SERVER['HTTP_REFERER']);
                        exit();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Nije odabrana kategorija za promjenu.</div>';
                    }
                }
                if (isset($_GET['delete'])) {
                    $stmt = $conn->prepare("DELETE FROM brands WHERE id = ?");
                    $stmt->bind_param('i', $_GET['delete']);
                    if ($stmt->execute()) {
                        $_SESSION['success'] = 'Brand je uspješno izbrisan!';
                        header("location: brands.php");
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
                                        <label for="category">Novi brand</label>
                                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime kategorije.
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Opis branda</label>
                                            <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"></textarea>

                                        </div>
                                    </div>
                                    <button type="submit" name="create" class="btn btn-primary">Dodaj</button>
                                </form>

                            </div>
                        </div>
                        <?php
                        if (isset($_GET['id'])) {
                            $sql = "SELECT * FROM brands WHERE id = ? LIMIT 1"; // SQL with parameters
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result(); // get the mysqli result
                            $nums = mysqli_num_rows($result);
                            //$user = $result->fetch_assoc(); // fetch data
                            $brand = mysqli_fetch_assoc($result);


                        ?>
                            <div class="card">
                                <div class="card-body">

                                    <form action="" method="post" enctype="multipart/form-data" name="edit" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="category">Promjena imena branda</label>
                                            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required value="<?php echo $brand['name'] ?>">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite ime branda.
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Opis branda</label>
                                                <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"><?php echo $brand['description'] ?></textarea>
                                                <div class="valid-feedback">
                                                    Super!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Molimo unesite opis.
                                                </div>
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
                                <h4 class="card-title"> Brendovi</h4>
                            </div>
                            <div class="card-body">
                                <form action="brands.php" method="post" enctype="multipart/form-data">
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
                                            $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 2;
                                            if (isset($searchKey)) {
                                                $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
                                                $sql = "SELECT * FROM brands WHERE name LIKE ?"; // SQL with parameters
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param('s', $search);
                                            } else {
                                                $sql = "SELECT * FROM brands"; // SQL with parameters
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
                                                $sql = $conn->prepare("SELECT * FROM brands WHERE name LIKE ? LIMIT $paginationStart, $limit");
                                                $sql->bind_param("s", $search);
                                                $sql->execute();
                                                $resultsBrands = $sql->get_result();
                                            } else {
                                                $sql = $conn->prepare("SELECT * FROM brands LIMIT $paginationStart, $limit");
                                                $sql->execute();
                                                $resultsBrands = $sql->get_result();
                                            }

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
                                                        <a class="page-link" href="<?php echo 'brands.php?page=' . $i . '&search=' . $searchKey ?>"><?php echo $i ?>
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
                                                        <a class="page-link" href="<?php echo 'brands.php?page=' . $i ?>"><?php echo $i ?>
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

<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();
$searchKey = searchKeyNoField();
$main = 'statuses';
$table = 'order_statuses';
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
                    if (createStatus()) {
                        unset($_SESSION['success']);
                        $_SESSION['success'] = 'Status je uspješno dodan!';
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                        Dogodila se greška.
                        </div>';
                    }
                }
                if (isset($_POST['edit'])) {
                    if (isset($_GET['id'])) {
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Nije odabran status za promjenu.</div>';
                    }
                }
                if (isset($_GET['delete'])) {
                    deleteStatus();
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
                                        <label for="category">Novi status</label>
                                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime statusa.
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Opis statusa</label>
                                            <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" name="create" class="btn btn-primary">Dodaj</button>
                                </form>
                            </div>
                        </div>
                        <?php
                        if (isset($_GET['id'])) {
                            $status = editStatus();
                        ?>
                            <div class="card">
                                <div class="card-body">

                                    <form action="" method="post" enctype="multipart/form-data" name="edit" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="category">Promjena imena statusa</label>
                                            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required value="<?php echo $status['name'] ?>">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite ime statusa.
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Opis statusa</label>
                                                <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"><?php echo $status['description'] ?></textarea>
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
                                <h4 class="card-title"> Statusi</h4>
                            </div>
                            <div class="card-body">
                                <form action="statuses.php" method="get" enctype="multipart/form-data">
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
                                                    Ime statusa
                                                </th>
                                                <th>
                                                    Opis statusa
                                                </th>
                                                <th class="text-center">Promjena</th>
                                                <th class="text-center">Brisanje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            list($resultsStatuses, $limit, $page, $prev, $next, $totoalPages) = getAll($table);
                                            while ($row = mysqli_fetch_assoc($resultsStatuses)) {
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
                                                        <a href="statuses.php?id=<?php echo $row['id'] ?>"><button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon">
                                                                <i class="tim-icons icon-settings"></i>
                                                            </button></a>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon " data-toggle="modal" data-target="#exampleModal1<?php echo $row['id'] ?>">
                                                            <i class="tim-icons icon-simple-remove"></i>
                                                        </button>
                                                    </td>
                                                    <?php include("includes/modals-statuses.php") ?>
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

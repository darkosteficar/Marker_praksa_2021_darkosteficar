<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();
$searchKey = searchKeyNoField();
$main = 'categories';
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
                    if (createCategory()) {
                        $_SESSION['success'] = 'Kategorija je uspješno dodana!';
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                        Dogodila se greška.
                        </div>';
                    }
                }
                if (isset($_POST['edit'])) {
                }
                if (isset($_GET['delete'])) {
                    deleteCategory();
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
                                        <label for="category">Nova kategorija</label>
                                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime kategorije.
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Opis kategorije</label>
                                            <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="inputState">Nadkategorija</label>
                                        <select id="inputState" class="form-control" style="background-color: gray;" name="parent">

                                            <option value="0">Glavna kategorija</option>
                                            <?php
                                            $stmt = $conn->prepare("SELECT * FROM categories");
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>

                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        <label for="inputState">Aktivnost</label>
                                        <select id="inputState" class="form-control" style="background-color: gray;" name="active">
                                            <option value="1">Aktivna</option>
                                            <option value="0">Neaktivna</option>

                                        </select>
                                    </div>

                                    <button type="submit" name="create" class="btn btn-primary">Dodaj</button>
                                </form>

                            </div>
                        </div>
                        <?php
                        if (isset($_GET['id'])) {
                            $category = editCategory();
                        ?>
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="post" enctype="multipart/form-data" name="edit" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="category">Promjena imena kategorije</label>
                                            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ime kategorije" required value="<?php echo $category['name'] ?>">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite ime kategorije.
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Opis kategorije</label>
                                                <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"><?php echo $category['description'] ?></textarea>
                                                <div class="valid-feedback">
                                                    Super!
                                                </div>
                                                <div class="invalid-feedback">
                                                    Molimo unesite opis kategorije.
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="inputState">Nadkategorija</label>
                                                <select id="inputState" class="form-control" style="background-color: gray;" name="parent">
                                                    <option value="np">Nemoj mijenjati</option>
                                                    <option value="0">Glavna kategorija</option>
                                                    <?php
                                                    $stmt = $conn->prepare("SELECT * FROM categories");
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group ">
                                                <label for="inputState">Aktivnost</label>
                                                <select id="inputState" class="form-control" style="background-color: gray;" name="active">
                                                    <?php if ($category['active'] == 1) {

                                                    ?>
                                                        <option value="1">Aktivna</option>
                                                        <option value="0">Neaktivna</option>
                                                    <?php } else { ?>
                                                        <option value="0">Neaktivna</option>
                                                        <option value="1">Aktivna</option>

                                                    <?php } ?>
                                                </select>
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
                                <h4 class="card-title"> Kategorije</h4>
                            </div>
                            <div class="card-body">

                                <form action="categories.php" method="get" enctype="multipart/form-data">
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
                                                    Ime kategorije
                                                </th>
                                                <th>
                                                    Opis kategorije
                                                </th>
                                                <th class="text-center">Promjena</th>
                                                <th class="text-center">Brisanje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $getCategories = getAll($main);
                                            $resultsCategories = $getCategories[0];
                                            $limit = $getCategories[1];
                                            $page = $getCategories[2];
                                            $prev = $getCategories[3];
                                            $next = $getCategories[4];
                                            $totoalPages = $getCategories[5];

                                            while ($row = mysqli_fetch_assoc($resultsCategories)) {
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
                                                        <a href="categories.php?id=<?php echo $row['id'] ?>"><button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon">
                                                                <i class="tim-icons icon-settings"></i>
                                                            </button></a>
                                                    </td>
                                                    <td class="td-actions text-center">
                                                        <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon " data-toggle="modal" data-target="#exampleModal<?php echo $row['id'] ?>">
                                                            <i class="tim-icons icon-simple-remove"></i>
                                                        </button>
                                                    </td>
                                                    <?php include("includes/modals-categories.php") ?>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <div>


                                    </div>


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

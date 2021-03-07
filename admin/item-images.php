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

if (isset($_POST['delete'])) {
    $images = array();
    $images = $_POST['images'];
    foreach ($images as $image) {
        $stmt = $conn->prepare("SELECT * FROM images WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $image);
        $stmt->execute();
        $result = $stmt->get_result();
        $imagePath = mysqli_fetch_assoc($result);

        $stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
        $stmt->bind_param('i', $image);
        if (!$stmt->execute()) {
            echo '<div class="alert alert-danger" role="alert"> Dogodila se greška </div>';
            break;
        } else {
            unlink('./images/' . $imagePath['path']);
            $_SESSION['success'] = 'Slike uspješno obrisane';
        }
    }
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h4 class="card-title"> Slike proizvoda</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">

                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM images WHERE item_id = ?");
                                    $stmt->bind_param('i', $_GET['item']);
                                    $stmt->execute();
                                    $results = $stmt->get_result();
                                    while ($row = mysqli_fetch_assoc($results)) {
                                    ?>

                                        <img src="<?php echo 'images/' . $row['path'] ?>" alt="" style="height: 400px;">
                                        <div class="form-check" style="<?php echo 'margin-left:' . 20 * $level . 'px' ?>">

                                            <label class="form-check-label" style="font-size: 15px;">
                                                <input class="form-check-input" name="images[]" type="checkbox" value="<?php echo $row['id'] ?>">
                                                <?php echo $row['path']; ?>
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>

                                    <?php }
                                    ?>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="margin-left: 10px;" name="delete">Izbriši</button>

                                    </div>
                                </form>
                            </div>
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

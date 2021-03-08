dD<?php
    include_once("includes/db.php");
    include_once("includes/functions.php");
    include_once("includes/admin-header.php");
    ob_start();


    if (isset($_POST['create'])) {
        createItem();
    }


    $stmt = $conn->prepare("SELECT * FROM item_category WHERE item_id = ?");
    $stmt->bind_param('i', $_GET['edit']);
    $stmt->execute();
    $resultsCategories = $stmt->get_result();
    $allCategories = array();
    while ($row = mysqli_fetch_assoc($resultsCategories)) {
        $allCategories[] = $row['category_id'];
    }

    $stmt = $conn->prepare("SELECT * FROM item_attribute WHERE item_id = ?");
    $stmt->bind_param('i', $_GET['edit']);
    $stmt->execute();
    $resultsCategories = $stmt->get_result();
    $allAttributes = array();
    while ($row = mysqli_fetch_assoc($resultsCategories)) {
        $allAttributes[] = $row['attribute_id'];
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
                                <h4 class="card-title"> Novi proizvod</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo  '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">


                                    <div class="form-group">

                                        <label for="" style="font-size: 20px;font-weight:600">Kategorije:</label>
                                        <?php
                                        $level_colors = array();
                                        $level_colors[] = 'blue';
                                        $level_colors[] = 'green';
                                        $level_colors[] = 'red';
                                        $level_colors[] = 'orange';

                                        echo '<button class="btn btn-primary">Mobiteli</button>';
                                        echo '<div class="dropdown-content2>"';
                                        display_children2(1, 0, $level_colors);
                                        echo '</div>';
                                        echo "<br>";
                                        display_children2(6, 0, $level_colors);

                                        ?>

                                    </div>

                                    <div class="form-group mt-5">
                                        <label for="" style="font-size: 20px;font-weight:600">Atributi:</label>
                                        <?php
                                        $stmt = $conn->prepare("SELECT DISTINCT name FROM attributes ");
                                        $stmt->execute();
                                        $results = $stmt->get_result();
                                        $attributeNames = array();
                                        while ($row = mysqli_fetch_assoc($results)) {
                                            $attributeNames[] = $row['name'];
                                        }

                                        foreach ($attributeNames as $name) {
                                            echo '<hr style="background-color:white">';
                                            $stmt = $conn->prepare("SELECT * FROM attributes WHERE name = ?");
                                            $stmt->bind_param('s', $name);
                                            $stmt->execute();
                                            $resultsAttributes = $stmt->get_result();
                                            while ($attribute = mysqli_fetch_assoc($resultsAttributes)) { ?>
                                                <div class="form-check">
                                                    <label class="form-check-label" style="font-size: 15px;">
                                                        <input class="form-check-input" name="attributes[]" type="checkbox" value="<?php echo $attribute['id'] ?>" <?php if (in_array($attribute['id'], $allAttributes)) echo "checked" ?>>
                                                        <?php echo $attribute['name'] . ' : ' . $attribute['value'] ?>
                                                        <span class="form-check-sign">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="margin-left: 10px;" name="create">Kreiraj</button>

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

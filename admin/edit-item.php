<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();


if (isset($_POST['edit'])) {
    editItem();
}



if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $_GET['edit']);
    $stmt->execute();
    $results = $stmt->get_result();
    $item = mysqli_fetch_assoc($results);
} else {
    header("location: items.php");
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
                                <h4 class="card-title"> Uređivanje proizvoda: <?php echo $item['name'] ?></h4>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo  '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['edit'] ?>">
                                    <div class="form-row ">
                                        <label for="uploadImageFile" style="font-size: 20px;font-weight:600"> &nbsp; Dodatne slike: &nbsp; </label>
                                        <input class="form-control" type="file" id="uploadImageFileAddPost" name="uploadImageFile[]" onchange="showImageHereFuncAddPost();" multiple />
                                        <label for="showImageHere" class="mr-3">Preview slika -></label>

                                        <div id="showImageHereAddPost"></div>
                                    </div>
                                    <input type="hidden" value="" name="post_id">
                                    <div class="form-group">
                                        <label for="" style="font-size: 20px;font-weight:600">Naziv</label>
                                        <input name="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $item['name'] ?>" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite naslov.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" style="font-size: 20px;font-weight:600">Opis</label>
                                        <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"><?php echo $item['description'] ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Brand</label>
                                            <select class="custom-select" id="inputState" class="form-control" name="brand" style="background-color: gray;">
                                                <option value="<?php echo $item['brand_id'] ?>">Bez promjene</option>
                                                <?php
                                                $stmt = $conn->prepare("SELECT * FROM brands");
                                                $stmt->execute();
                                                $results = $stmt->get_result();
                                                while ($row = mysqli_fetch_assoc($results)) {
                                                ?>
                                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>

                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Osnovna cijena</label>
                                            <input name="base_price" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $item['base_price'] ?>" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite osnovnu cijenu.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Popust</label>
                                            <input name="discount" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $item['discount'] ?>" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite popust.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Dostupna količina</label>
                                            <input name="avaliable_stock" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $item['avaliable_stock'] ?>" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite dostupnu količinu.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Zabrani naručivanje: &nbsp;</label>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="allow_resupply" id="inlineRadio1" value="1" <?php if ($item['allow_resupply'] == 1) echo "checked" ?>> Da
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="allow_resupply" id="inlineRadio2" value="0" <?php if ($item['allow_resupply'] == 0) echo "checked" ?>> Ne
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Aktivno: &nbsp;</label>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="active" id="inlineRadio1" value="1" <?php if ($item['active'] == 1) echo "checked" ?>> Da
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="active" id="inlineRadio2" value="0" <?php if ($item['active'] == 0) echo "checked" ?>> Ne
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" style="font-size: 20px;font-weight:600">Izdvojeno: &nbsp;</label>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="highlighted" id="inlineRadio1" value="1" <?php if ($item['highlighted'] == 1) echo "checked" ?>> Da
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="highlighted" id="inlineRadio2" value="0" <?php if ($item['highlighted'] == 0) echo "checked" ?>> Ne
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="form-group">

                                            <label for="" style="font-size: 20px;font-weight:600">Kategorije:</label>
                                            <?php
                                            $level_colors = array();
                                            $level_colors[] = 'blue';
                                            $level_colors[] = 'green';
                                            $level_colors[] = 'red';
                                            $level_colors[] = 'orange';

                                            displayCats();

                                            ?>

                                        </div>

                                        <div class="form-group">
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

                                <button type="submit" class="btn btn-primary" style="margin-left: 10px;" name="edit">Spremi promjene</button>

                            </div>
                            </form><a href="items.php">
                                <button class="btn btn-danger btn-simple" style="margin-left: 10px;">Povratak</button>
                            </a>
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

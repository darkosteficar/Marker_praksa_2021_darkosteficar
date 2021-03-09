<?php
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
                                    <div class="form-row ">
                                        <label for="uploadImageFile"> &nbsp; Slike: &nbsp; </label>
                                        <input class="form-control" type="file" id="uploadImageFileAddPost" name="uploadImageFile[]" onchange="showImageHereFuncAddPost();" multiple required />
                                        <label for="showImageHere" class="mr-3">Preview slika -></label>
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Slike su obavezne.
                                        </div>
                                        <div id="showImageHereAddPost"></div>
                                    </div>
                                    <input type="hidden" value="" name="post_id">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Naziv</label>
                                        <input name="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite naslov.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Opis</label>
                                        <textarea name="description" class="form-control" id="post_content" rows="7" required oninvalid="this.setCustomValidity('Unesite sadržaj objave!')" oninput="this.setCustomValidity('')"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="inputState">Brend ( Trenutni brend: )</label>
                                            <select class="custom-select" id="inputState" class="form-control" name="brand" style="background-color: gray;">
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
                                            <label for="exampleInputEmail1">Osnovna cijena</label>
                                            <input name="base_price" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite osnovnu cijenu.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Popust</label>
                                            <input name="discount" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite popust.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Dostupna količina</label>
                                            <input name="avaliable_stock" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                            <div class="valid-feedback">
                                                Super!
                                            </div>
                                            <div class="invalid-feedback">
                                                Molimo unesite dostupnu količinu.
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputState">Zabrani naručivanje: &nbsp;</label>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="allow_resupply" id="inlineRadio1" value="1" checked> Da
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="allow_resupply" id="inlineRadio2" value="0" checked> Ne
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputState">Aktivno: &nbsp;</label>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="active" id="inlineRadio1" value="1" checked> Da
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="active" id="inlineRadio2" value="0" checked> Ne
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputState">Izdvojeno: &nbsp;</label>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="highlighted" id="inlineRadio1" value="1" checked> Da
                                                    <span class="form-check-sign"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="highlighted" id="inlineRadio2" value="0" checked> Ne
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

                                            display_children(2, 0, $level_colors);
                                            echo "<br>";
                                            display_children(6, 0, $level_colors);

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

<?php

include("includes/db.php");



// Brands



function getBrandData()
{
    global $conn;
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
    return array($resultsBrands, $limit, $page, $prev, $next, $totoalPages);
}


function createBrand()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $stmt = $conn->prepare("INSERT INTO brands (name,description) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $description);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}


function updateBrand()
{
    global $conn;
    $name = $_POST['name'];
    $description = $_POST['description'];
    $id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE brands SET name = ?,description = ? WHERE id = ? ");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();
    $_SESSION['success'] = 'Brand ' . $name . ' je uspješno promjenjen!';
    header("location:" . $_SERVER['HTTP_REFERER']);
    exit();
}

function deleteBrand()
{
    global $conn;
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

function editBrand()
{
    global $conn;
    $sql = "SELECT * FROM brands WHERE id = ? LIMIT 1"; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $nums = mysqli_num_rows($result);
    //$user = $result->fetch_assoc(); // fetch data
    $brand = mysqli_fetch_assoc($result);
    return $brand;
}

// End Brands



// Statuses

function createStatus()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $stmt = $conn->prepare("INSERT INTO order_statuses (name,description) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $description);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// End Statuses



// Attributes

function createAttribute()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $value = htmlentities($_POST['value']);
    $stmt = $conn->prepare("INSERT INTO attributes (name,value) VALUES (?,?)");
    $stmt->bind_param("ss", $name, $value);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Atribut ' . $name . ' je uspješno dodan!';
        return true;
    }
    return false;
}

// End attributes




// Categories

function createCategory()
{
    global $conn;
    $name = htmlentities($_POST['name']);
    $description = htmlentities($_POST['description']);
    $active = htmlentities($_POST['active']);
    $parent = htmlentities($_POST['parent']);
    if ($_POST['parent'] != 0) {
        $stmt = $conn->prepare("INSERT INTO categories (name,description,active,parent_id) VALUES (?,?,?,?)");
        $stmt->bind_param("ssii", $name, $description, $active, $parent);
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name,description,active) VALUES (?,?,?)");
        $stmt->bind_param("ssi", $name, $description, $active);
    }
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

// End categories



// Items

function createItem()
{
    $checker = checkImages();
    if ($checker === count($_FILES["uploadImageFile"]["name"])) {

        global $conn;
        $name = htmlentities($_POST['name']);
        $description = htmlentities($_POST['description']);
        $brand = htmlentities($_POST['brand']);
        $active = htmlentities($_POST['brand']);
        $base_price = htmlentities($_POST['base_price']);
        $discount = htmlentities($_POST['discount']);
        $avaliable_stock = htmlentities($_POST['avaliable_stock']);
        $allow_resupply = htmlentities($_POST['allow_resupply']);
        $active = htmlentities($_POST['active']);
        $highlighted = htmlentities($_POST['highlighted']);
        $categories = $_POST['categories'];
        $attributes = $_POST['attributes'];


        $stmt = $conn->prepare("INSERT INTO items (name,brand_id,base_price,discount,avaliable_stock,allow_resupply,highlighted,active,description) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("siddiiiis", $name, $brand, $base_price, $discount, $avaliable_stock, $allow_resupply, $highlighted, $active, $description);
        if ($stmt->execute()) {
            $item_id = mysqli_insert_id($conn);
            foreach ($attributes as $atribute) {
                $stmt = $conn->prepare("INSERT INTO item_attribute (item_id,attribute_id) VALUES (?,?)");
                $stmt->bind_param("ii", $item_id, $atribute);
                $stmt->execute();
            }
            foreach ($categories as $category) {
                $stmt = $conn->prepare("INSERT INTO item_category (item_id,category_id) VALUES (?,?)");
                $stmt->bind_param("ii", $item_id, $category);
                $stmt->execute();
            }
            $target_dir = "./images/";
            for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {

                $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
                $fileName = $_FILES["uploadImageFile"]["name"][$i];
                $target_file = $target_dir . $uploadfile;
                $rng = 1;
                $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES["uploadImageFile"]["name"][$i]));
                while (file_exists($target_dir . $newfilename)) {
                    $newfilename = $newfilename . $rng;
                    $rng++;
                }

                if (move_uploaded_file($uploadfile, "$target_dir" . $newfilename)) {

                    $stmtImages = $conn->prepare("INSERT INTO images (item_id,path) VALUES (?,?)");
                    $stmtImages->bind_param("is",  $item_id, $newfilename);
                    $stmtImages->execute();
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                    Greška sa postavljanjem slike.
                        </div>';
                }
            }
            $_SESSION['success'] = "Proizvod " . $name . " uspješno kreiran";
        } else {
            echo '<div class="alert alert-danger" role="alert">Greška sa postavljanjem objave.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
            Greška sa postavljanjem slike.
          </div>';;
    }
}



function editItem()
{
    $checker = 'ni';
    if ($_FILES['uploadImageFile']['name'][0] != '') {
        $checker = checkImages();
    }
    if ($checker === count($_FILES["uploadImageFile"]["name"]) || $checker == 'ni') {

        global $conn;
        $id = htmlentities($_POST['id']);
        $name = htmlentities($_POST['name']);
        $description = htmlentities($_POST['description']);
        $brand = htmlentities($_POST['brand']);
        $active = htmlentities($_POST['brand']);
        $base_price = htmlentities($_POST['base_price']);
        $discount = htmlentities($_POST['discount']);
        $avaliable_stock = htmlentities($_POST['avaliable_stock']);
        $allow_resupply = htmlentities($_POST['allow_resupply']);
        $active = htmlentities($_POST['active']);
        $highlighted = htmlentities($_POST['highlighted']);
        $categories = $_POST['categories'];
        $attributes = $_POST['attributes'];


        $stmt = $conn->prepare("UPDATE items SET name = ?,brand_id = ?,base_price = ?,discount = ?,avaliable_stock = ?,allow_resupply = ?,highlighted = ?,active = ?,description = ? WHERE id = ?");
        $stmt->bind_param("siddiiiisi", $name, $brand, $base_price, $discount, $avaliable_stock, $allow_resupply, $highlighted, $active, $description, $id);
        if ($stmt->execute()) {
            resetItemCategories();
            resetItemBrand();
            foreach ($attributes as $atribute) {
                $stmt = $conn->prepare("INSERT INTO item_attribute (item_id,attribute_id) VALUES (?,?)");
                $stmt->bind_param("ii", $id, $atribute);
                $stmt->execute();
            }
            foreach ($categories as $category) {
                $stmt = $conn->prepare("INSERT INTO item_category (item_id,category_id) VALUES (?,?)");
                $stmt->bind_param("ii", $id, $category);
                $stmt->execute();
            }
            $target_dir = "./images/";
            if ($checker != 'ni') {
                for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {

                    $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
                    $fileName = $_FILES["uploadImageFile"]["name"][$i];
                    $target_file = $target_dir . $uploadfile;
                    $rng = 1;
                    $newfilename = date('dmYHis') . str_replace(" ", "", basename($_FILES["uploadImageFile"]["name"][$i]));
                    while (file_exists($target_dir . $newfilename)) {
                        $newfilename = $newfilename . $rng;
                        $rng++;
                    }

                    if (move_uploaded_file($uploadfile, "$target_dir" . $newfilename)) {

                        $stmtImages = $conn->prepare("INSERT INTO images (item_id,path) VALUES (?,?)");
                        $stmtImages->bind_param("is",  $id, $newfilename);
                        $stmtImages->execute();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                    Greška sa postavljanjem slike.
                        </div>';
                    }
                }
            }

            $_SESSION['success'] = "Proizvod uspješno ažuriran";
        } else {
            echo '<div class="alert alert-danger" role="alert">Greška sa promjenom proizvoda.</div>';
            echo $conn->error;
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
            Greška sa postavljanjem slike.
          </div>';;
    }
}


function display_children($parent, $level, $level_colors)
{

    // retrieve all children of $parent 
    global $conn, $allCategories;
    $result = $conn->query('SELECT id,name FROM categories ' . 'WHERE parent_id="' . $parent . '";');
    // display each child 

    while ($row = mysqli_fetch_array($result)) {
        // indent and display the title of this child 
?>
        <div class="form-check" style="<?php echo 'margin-left:' . 20 * $level . 'px' ?>">
            <label class="form-check-label" style="font-size: 15px;">
                <input class="form-check-input" name="categories[]" type="checkbox" value="<?php echo $row['id'] ?>" <?php if (in_array($row['id'], $allCategories)) echo "checked" ?>>
                <?php echo $row['name']; ?>
                <span class="form-check-sign">
                    <span class="check"></span>
                </span>
            </label>
        </div>
    <?php

        display_children($row['id'], $level + 1, $level_colors);
    }
}


function display_children2($parent, $level, $level_colors)
{

    // retrieve all children of $parent 
    global $conn, $allCategories;
    $result = $conn->query('SELECT id,name FROM categories ' . 'WHERE parent_id="' . $parent . '";');
    // display each child 
    ?>
    <ul>
        <?php
        while ($row = mysqli_fetch_array($result)) {
            // indent and display the title of this child 
        ?>
            <a href="item.php?id=<?php echo $row['id'] ?>">
                <li><?php echo $row['name'] ?></li>
            </a>
        <?php

            display_children2($row['id'], $level + 1, $level_colors);
        }
        ?>
    </ul>
    <?php
}

// End Items




// Utilities

function checkImages()
{
    $checker = 0;
    $target_dir = "./images/";

    for ($i = 0; $i < count($_FILES["uploadImageFile"]["name"]); $i++) {
        $uploadfile = $_FILES["uploadImageFile"]["tmp_name"][$i];
        $fileName = $_FILES["uploadImageFile"]["name"][$i];
        $target_file = $target_dir . $fileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($uploadfile);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="alert alert-danger" role="alert">
        Datoteka nije slika
      </div>';;
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["uploadImageFile"]["size"][$i] > 50000000) {
            echo '<div class="alert alert-danger" role="alert">
        Slika je prevelika
      </div>';;
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo '<div class="alert alert-danger" role="alert">
        Samo JPG, JPEG, PNG & GIF formati su dopušteni.
      </div>';;
            $uploadOk = 0;
        }
        if ($uploadOk === 1) {
            $checker++;
        }
    }

    return $checker;
}


function resetItemCategories()
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM item_category WHERE item_id = ?");
    $stmt->bind_param('i', $_POST['id']);
    $stmt->execute();
}

function resetItemBrand()
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM item_category WHERE item_id = ?");
    $stmt->bind_param('i', $_POST['id']);
    $stmt->execute();
}

function pagination($main)
{
    global $totoalPages, $page, $prev, $searchKey, $next, $search;
    if (isset($search)) { ?>
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
                        <a class="page-link" href="<?php echo $main . '.php?page=' . $i . '&search=' . $searchKey ?>"><?php echo $i ?>
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
                        <a class="page-link" href="<?php echo $main . '.php?page=' . $i ?>"><?php echo $i ?>
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
<?php }
}

function searchKeyNoField()
{
    $searchKey = null;
    if (isset($_POST['search'])) {
        $searchKey = $_POST['key'];
    } else if (isset($_GET['search'])) {
        $searchKey = $_GET['search'];
    }
    return $searchKey;
}

function searchKeyWithField()
{
    if (isset($_POST['search'])) {
        $searchKey = $_POST['key'];
        $searchField = $_POST['field'];
    } else if (isset($_GET['search']) && isset($_GET['field'])) {
        $searchKey = $_GET['search'];
        $searchField = $_GET['field'];
    }
    return array($searchKey, $searchField);
}



// End Utilites
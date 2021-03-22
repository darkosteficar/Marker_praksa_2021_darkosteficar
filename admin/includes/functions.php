<?php

include("includes/db.php");

function getAll($table)
{
    global $conn, $searchKey;
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 2;
    if (isset($searchKey)) {
        $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
        $sql = "SELECT * FROM $table WHERE name LIKE ?"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
    } else {
        $sql = "SELECT * FROM $table"; // SQL with parameters
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
        $sql = $conn->prepare("SELECT * FROM $table WHERE name LIKE ? LIMIT $paginationStart, $limit");
        $sql->bind_param("s", $search);
        $sql->execute();
        $resultsBrands = $sql->get_result();
    } else {
        $sql = $conn->prepare("SELECT * FROM $table LIMIT $paginationStart, $limit");
        $sql->execute();
        $resultsBrands = $sql->get_result();
    }
    $rows = mysqli_num_rows($resultsBrands);
    return array($resultsBrands, $limit, $page, $prev, $next, $totoalPages);
}

function getAllWithField($table)
{
    global $conn, $searchKey, $searchField;
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 2;
    if (isset($searchField) && isset($searchKey)) {
        $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
        $sql = "SELECT * FROM $table WHERE $searchField LIKE ?"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
    } else if (isset($searchKey)) {
        $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
        $sql = "SELECT * FROM $table WHERE name LIKE ?"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
    } else {
        $sql = "SELECT * FROM $table"; // SQL with parameters
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
    if (isset($searchField) && isset($searchKey)) {
        $sql = $conn->prepare("SELECT * FROM $table WHERE $searchField LIKE ? LIMIT $paginationStart, $limit");
        $sql->bind_param("s", $search);
        $sql->execute();
        $resultsBrands = $sql->get_result();
    } else  if (isset($searchKey)) {
        $sql = $conn->prepare("SELECT * FROM $table WHERE name LIKE ? LIMIT $paginationStart, $limit");
        $sql->bind_param("s", $search);
        $sql->execute();
        $resultsBrands = $sql->get_result();
    } else {
        $sql = $conn->prepare("SELECT * FROM $table LIMIT $paginationStart, $limit");
        $sql->execute();
        $resultsBrands = $sql->get_result();
    }
    $rows = mysqli_num_rows($resultsBrands);
    return array($resultsBrands, $limit, $page, $prev, $next, $totoalPages);
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Brands

function createBrand()
{
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['description'])) {
        echo "<div class='alert alert-danger'>Polje opis je obavezno</div>";
        $checker = 1;
    }
    if ($checker != 1) {
        global $conn;
        $name = htmlentities($_POST['name']);
        $description = htmlentities($_POST['description']);
        $stmt = $conn->prepare("INSERT INTO brands (name,description) VALUES (?,?)");
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            return true;
        }
    }
}


function updateBrand()
{
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['description'])) {
        echo "<div class='alert alert-danger'>Polje opis je obavezno</div>";
        $checker = 1;
    }
    if ($checker != 1) {
        global $conn;
        $name = htmlentities($_POST['name']);
        $description = htmlentities($_POST['description']);
        $id = $_GET['id'];
        $stmt = $conn->prepare("UPDATE brands SET name = ?,description = ? WHERE id = ? ");
        $stmt->bind_param("ssi", $name, $description, $id);
        $stmt->execute();
        $_SESSION['success'] = 'Brand ' . $name . ' je uspješno promjenjen!';
        header("location:" . $_SERVER['HTTP_REFERER']);
        exit();
    }
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
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['description'])) {
        echo "<div class='alert alert-danger'>Polje opis je obavezno</div>";
        $checker = 1;
    }
    if ($checker != 1) {
        global $conn;
        $name = htmlentities($_POST['name']);
        $description = htmlentities($_POST['description']);
        $id = $_GET['id'];
        $stmt = $conn->prepare("UPDATE statuses SET name = ?,description = ? WHERE id = ? ");
        $stmt->bind_param("ssi", $name, $description, $id);
        $stmt->execute();
        $_SESSION['success'] = 'Status ' . $name . ' je uspješno promjenjen!';
        header("location:" . $_SERVER['HTTP_REFERER']);
        exit();
    }
}


function updateStatus()
{
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['description'])) {
        echo "<div class='alert alert-danger'>Polje opis je obavezno</div>";
        $checker = 1;
    }
    if ($checker != 1) {
        global $conn;
        $name = $_POST['name'];
        $description = $_POST['description'];
        $id = $_GET['id'];
        $stmtImages = $conn->prepare("UPDATE order_statuses SET name = ?,description = ? WHERE id = ? ");
        $stmtImages->bind_param("ssi", $name, $description, $id);
        if ($stmtImages->execute()) {
            $_SESSION['success'] = 'Status ' . $name . ' je uspješno promjenjen!';
            header("location: statuses.php");
            exit();
        } else {
            return false;
        }
    }
    return false;
}

function editStatus()
{
    global $conn;
    $sql = "SELECT * FROM order_statuses WHERE id = ? LIMIT 1"; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $nums = mysqli_num_rows($result);
    //$user = $result->fetch_assoc(); // fetch data
    $status = mysqli_fetch_assoc($result);
    return $status;
}

function deleteStatus()
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM order_statuses WHERE id = ?");
    $stmt->bind_param('i', $_GET['delete']);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Status je uspješno izbrisan!';
        header("location: statuses.php");
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert"> Status nije izbrisan. </div>';
    }
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

function editAttribute()
{
    global $conn;
    $sql = "SELECT * FROM attributes WHERE id = ? LIMIT 1"; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $nums = mysqli_num_rows($result);
    //$user = $result->fetch_assoc(); // fetch data
    $attribute = mysqli_fetch_assoc($result);
    return $attribute;
}

function updateAttribute()
{
    global $conn;
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


function deleteAttribute()
{
    global $conn;
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

// End attributes




// Categories

function createCategory()
{
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['description'])) {
        echo "<div class='alert alert-danger'>Polje opis je obavezno</div>";
        $checker = 1;
    }
    if ($checker != 1) {
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
    }
    return false;
}


function editCategory()
{
    global $conn;
    $sql = "SELECT * FROM categories WHERE id = ? LIMIT 1"; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $nums = mysqli_num_rows($result);
    //$user = $result->fetch_assoc(); // fetch data
    $category = mysqli_fetch_assoc($result);
    return $category;
}

function updateCategory()
{
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['description'])) {
        echo "<div class='alert alert-danger'>Polje opis je obavezno</div>";
        $checker = 1;
    }
    if ($checker != 1) {
        global $conn;
        if (isset($_GET['id'])) {
            $name = htmlentities($_POST['name']);
            $description = htmlentities($_POST['description']);
            $id = $_GET['id'];
            $parent = htmlentities($_POST['parent']);
            $active = htmlentities($_POST['active']);
            if ($parent == 'np') {
                $stmtImages = $conn->prepare("UPDATE categories SET name = ?,description = ?,active = ? WHERE id = ? ");
                $stmtImages->bind_param("ssii", $name, $description, $active, $id);
            } else {
                $stmtImages = $conn->prepare("UPDATE categories SET name = ?,description = ?,active = ?,parent_id = ? WHERE id = ? ");
                $stmtImages->bind_param("ssiii", $name, $description, $active,  $parent, $id);
            }
            $stmtImages->execute();
            $_SESSION['success'] = 'Kategorija ' . $name . ' je uspješno promjenjena!';
            header("location: categories.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Nije odabrana kategorija za promjenu.</div>';
        }
    }
    return false;
}

function deleteCategory()
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param('i', $_GET['delete']);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Kategorija je uspješno izbrisana!';
    } else {
        echo '<div class="alert alert-danger" role="alert"> Dogodila se greška. </div>';
    }
}

// End categories



// Items


function getAllItems()
{
    global $conn, $searchField, $searchKey;
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 2;
    if (isset($searchKey)) {
        $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
        $sql = "SELECT items.name,items.id,items.base_price,brands.name FROM items INNER JOIN brands ON brands.id = items.brand_id WHERE items.$searchField LIKE ?"; // SQL with parameters
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
        $sql = $conn->prepare("SELECT items.name,items.discount,items.active,items.highlighted,items.id,items.base_price,items.description,items.avaliable_stock,items.allow_resupply,brands.name AS brand FROM items INNER JOIN brands ON brands.id = items.brand_id WHERE items.$searchField LIKE ? LIMIT $paginationStart, $limit");
        $sql->bind_param("s", $search);
        $sql->execute();
        $resultsOrders = $sql->get_result();
    } else {
        $sql = $conn->prepare("SELECT items.name,items.discount,items.active,items.highlighted,items.id,items.base_price,items.description,items.avaliable_stock,items.allow_resupply,brands.name AS brand FROM items INNER JOIN brands ON brands.id = items.brand_id LIMIT $paginationStart, $limit");
        $sql->execute();
        $resultsOrders = $sql->get_result();
    }
    return array($resultsOrders, $limit, $page, $prev, $next, $totoalPages);
}


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


function display_children($parent, $level)
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

        display_children($row['id'], $level + 1);
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


function displayCats()
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM categories WHERE parent_id = 0 ");
    $stmt->execute();
    $results = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($results)) {

        display_children($row['id'], 0);
    }
}

// End Items





// Buyers

function createBuyer()
{
    $checker = validateBuyer();
    if ($checker != 1) {
        global $conn;
        $name = htmlentities($_POST['name']);
        $surname = htmlentities($_POST['surname']);
        $email = htmlentities($_POST['email']);
        $address = htmlentities($_POST['address']);
        $stmt = $conn->prepare("INSERT INTO buyers (name,surname,email,address) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $surname, $email, $address);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Kupac je uspješno kreiran!';
            return true;
        }
    }
}

function deleteBuyer()
{
    global $conn;
    $user_id = $_GET['delete'];
    $sql = "DELETE FROM buyers WHERE id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Kupac je uspješno izbrisan!';
        $prevUrl = $_SESSION['prevUrl'];
        header("location: $prevUrl ");
        unset($_SESSION['prevUrl']);
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert"> Dogodila se greška. </div>';
    }
}

function updateBuyer()
{
    $checker = validateBuyer();
    if ($checker == 0) {
        global $conn;
        $name = test_input($_POST['name']);
        $surname = test_input($_POST['surname']);
        $email = test_input($_POST['email']);
        $address = test_input($_POST['address']);
        $id = test_input($_POST['id']);
        $stmt = $conn->prepare("UPDATE buyers SET name = ?,surname = ?,email=? , address = ? WHERE id = ? ");
        $stmt->bind_param("ssssi", $_POST['name'], $_POST['surname'], $_POST['email'], $_POST['address'], $_POST['id']);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Kupac je uspješno promjenjen!';
            return true;
        }
    }
}


function validateBuyer()
{
    $checker = 0;
    if (empty($_POST['name'])) {
        echo "<div class='alert alert-danger'>Polje ime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['surname'])) {
        echo "<div class='alert alert-danger'>Polje prezime je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['email'])) {
        echo "<div class='alert alert-danger'>Polje email je obavezno</div>";
        $checker = 1;
    }
    if (empty($_POST['address'])) {
        echo "<div class='alert alert-danger'>Polje adresa je obavezno</div>";
        $checker = 1;
    }
    return $checker;
}

// End Buyers




// Orders

function createOrder()
{
    $checker = 0;
    $checker = validateBuyer();

    if ($checker == 0) {
        global $conn;
        $name = test_input($_POST['name']);
        $surname = test_input($_POST['surname']);
        $email = test_input($_POST['email']);
        $address = test_input($_POST['address']);
        $time = date('Y-m-d H:i:s');;
        $status = test_input($_POST['brand']);
        $stmt = $conn->prepare("INSERT INTO orders (status_id ,name ,surname ,email,address,time) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("isssss", $status, $name, $surname, $email, $address, $time);
        if ($stmt->execute()) {
            return true;
        }
    }
    return false;
}


function getAllOrders()
{
    global $conn, $searchKey, $searchField;
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
    if (isset($searchKey)) {
        $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
        $sql = "SELECT * FROM orders WHERE $searchField LIKE ?"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
    } else {
        $sql = "SELECT * FROM orders"; // SQL with parameters
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
        $sql = $conn->prepare("SELECT orders.id,orders.status_id,orders.name,orders.surname,orders.address,orders.email,orders.time,order_statuses.name AS status FROM orders INNER JOIN order_statuses ON order_statuses.id = orders.status_id WHERE orders.$searchField LIKE ? LIMIT $paginationStart, $limit");
        $sql->bind_param("s", $search);
        $sql->execute();
        $resultsOrders = $sql->get_result();
    } else {
        $sql = $conn->prepare("SELECT orders.id,orders.name,orders.status_id,orders.surname,orders.address,orders.email,orders.time,order_statuses.name AS status FROM orders INNER JOIN order_statuses ON order_statuses.id = orders.status_id LIMIT $paginationStart, $limit");
        $sql->execute();
        $resultsOrders = $sql->get_result();
    }
    return array($resultsOrders, $limit, $page, $prev, $next, $totoalPages);
}

function updateOrder()
{
    $checker = 0;
    $checker = validateBuyer();
    if (empty($_POST['time'])) {
        echo "<div class='alert alert-danger'>Polje vrijeme je obavezno</div>";
        $checker = 1;
    }
    if ($checker == 0) {
        global $conn;
        $name = test_input($_POST['name']);
        $surname = test_input($_POST['surname']);
        $email = test_input($_POST['email']);
        $address = test_input($_POST['address']);
        $time = test_input($_POST['time']);
        $status = test_input($_POST['brand']);
        $id = test_input($_POST['id']);
        $stmt = $conn->prepare("UPDATE orders SET status_id = ?,name = ?,surname = ?,email=? , address = ?,time = ? WHERE id = ? ");
        $stmt->bind_param("isssssi", $status, $name, $surname, $email, $address, $time, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Narudžba je uspješno promjenjena!';
            return true;
        }
    }
    echo $conn->error;
}

function deleteOrder()
{
    global $conn;
    $order = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Narudžba je uspješno izbrisana!';
        $prevUrl = $_SESSION['prevUrl'];
        header("location: $prevUrl ");
        unset($_SESSION['prevUrl']);
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert"> Dogodila se greška. </div>';
        echo $conn->error;
    }
}



// End Orders






// Add Order Items

function getAllNotInOrder()
{
    global $conn, $searchField, $searchKey;
    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
    if (isset($searchKey)) {
        $search = "%" .  mysqli_real_escape_string($conn, $searchKey) . "%";
        $field =   mysqli_real_escape_string($conn, $searchField);
        $sql = "SELECT * FROM buyers WHERE $field LIKE ?"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
    } else {
        $sql = "SELECT DISTINCT items.*,order_items.* FROM items LEFT JOIN order_items
        ON order_items.item_id = items.id AND order_items.order_id = ?
        WHERE order_items.order_id IS NULL"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['order']);
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
        $sql = $conn->prepare("SELECT * FROM buyers WHERE name LIKE '?' LIMIT $paginationStart, $limit");
        $stmt->bind_param('s', $search);
        $sql->execute();
        $resultsOrderItems = $sql->get_result();
        echo $search;
    } else {
        $sql = "SELECT DISTINCT items.*,order_items.* FROM items LEFT JOIN order_items
        ON order_items.item_id = items.id AND order_items.order_id = ?
        WHERE order_items.order_id IS NULL LIMIT $paginationStart, $limit"; // SQL with parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['order']);
        $stmt->execute();
        $resultsOrderItems = $stmt->get_result();
    }
    return array($resultsOrderItems, $limit, $page, $prev, $next, $totoalPages);
}


// End Add Order Items












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
?>
    <!--Pagination -->
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
    <!--Pagination -->
<?php

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



function display_children3($parent, $level)
{

    $array = array();
    // retrieve all children of $parent 
    global $conn, $allCategories;
    $result = $conn->query('SELECT id,name FROM categories ' . 'WHERE parent_id="' . $parent . '";');
    // display each child 

    while ($row = mysqli_fetch_array($result)) {
        // indent and display the title of this child 
        $array[] = $row['id'];

        display_children($row['id'], $level + 1);
    }
    return $array;
}




// End Utilites
<?php

include("includes/db.php");



// Brands
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


        $stmt = $conn->prepare("INSERT INTO items (name,brand_id,base_price,discount,avaliable_stock,allow_resupply,highlighted,active,description) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("siddiiiis", $name, $brand, $base_price, $discount, $avaliable_stock, $allow_resupply, $highlighted, $active, $description);
        if ($stmt->execute()) {
            $item_id = mysqli_insert_id($conn);
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
            echo '<div class="alert alert-success" role="alert">
            Objava uspješno postavljena.
          </div>';
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
    $cheker = 'ni';
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


        $stmt = $conn->prepare("UPDATE items SET name = ?,brand_id = ?,base_price = ?,discount = ?,avaliable_stock = ?,allow_resupply = ?,highlighted = ?,active = ?,description = ? WHERE id = ?");
        $stmt->bind_param("siddiiiisi", $name, $brand, $base_price, $discount, $avaliable_stock, $allow_resupply, $highlighted, $active, $description, $id);
        if ($stmt->execute()) {
            resetItemCategories();
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



// End Utilites
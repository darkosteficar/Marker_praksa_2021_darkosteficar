<?php


// Items

function getAllHighlighted()
{
    global $conn;
    $stmt = $conn->prepare("SELECT DISTINCT items.name,items.id,items.base_price,items.discount,images.path FROM items INNER JOIN images ON images.item_id = items.id WHERE items.highlighted = 1 GROUP BY items.name"); // Slike 
    $stmt->execute();
    $results = $stmt->get_result();
    return $results;
}


function getCartContent($cart_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT items.id,items.name,items.base_price,items.discount,cart_items1.quantity FROM
    items INNER JOIN cart_items1 ON cart_items1.item_id = items.id WHERE cart_items1.cart_id = ?");
    $stmt->bind_param('s', $cart_id);
    $stmt->execute();
    $results = $stmt->get_result();
    return $results;
}



function getItem($id)
{
    $all = array();
    global $conn;
    $stmt = $conn->prepare("SELECT items.name,items.base_price,items.discount,items.description,items.avaliable_stock,brands.name AS brand FROM items INNER JOIN brands ON brands.id = items.brand_id WHERE items.id = ? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $all["item"] = $stmt->get_result();
    $stmt = $conn->prepare("SELECT path FROM images WHERE item_id = ? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $all["images"] = $stmt->get_result(); // asocijativan
    $stmt = $conn->prepare("SELECT name FROM categories INNER JOIN item_category ON item_category.category_id = categories.id WHERE item_category.item_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $all["categories"] = $stmt->get_result();
    return $all;
}


function getImage($id)
{
    global $conn;
    $stmtImage = $conn->prepare("SELECT path FROM images WHERE item_id = ? LIMIT 1");
    $stmtImage->bind_param('i', $id);
    $stmtImage->execute();
    $Image = $stmtImage->get_result();
    $ImagePath = mysqli_fetch_assoc($Image);
    $ImagePath = $ImagePath['path'];
    return $ImagePath;
}

// End Items



// Cart

function cartInfo($row)
{
    global $total;
    $image = getImage($row['id']);
    $overallNoDiscount = $row['base_price'] * $row['quantity'];
    $pricing = 100 - $row['discount'];
    $pricing = $pricing * 0.01;
    $pricing = $row['base_price'] * $pricing;
    $overallDiscount = $pricing * $row['quantity'];
    $total[] = $overallDiscount;
    $overallDiscount = number_format((float)$overallDiscount, 2, '.', ',');
    return array($image, $overallNoDiscount, $overallDiscount);
}


function cartDelete($cart_id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cart_items1 WHERE cart_id = ? AND item_id = ?");
    $stmt->bind_param('si', $cart_id, $_GET['delete']);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Proizvod ' . $_GET['item'] . ' je uspješno izbrisan!';
        header("location: cart.php ");
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert"> Dogodila se greška. </div>';
        echo $conn->error;
    }
}


function cartUpdate()
{
    global $conn, $cart_id;
    $item = test_input($_POST['item']);
    $quantity = test_input($_POST['quantity']);
    $avaliableStock = getAvaliableStock($item);

    if (is_int($quantity)) {
        if ($quantity > 0 && $quantity <=  $avaliableStock) {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE item_id = ? AND cart_id = ? ");
            $stmt->bind_param("iis", $quantity, $item, $cart_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Narudžba je uspješno promjenjena!';
                header("location: cart.php ");
                exit();
            }
        } else {
            $_SESSION['error'] = 'Unesena količina za ovaj proizvod je neispravna,maksimalna količina: ' . $avaliableStock;
        }
    } else {
        $_SESSION['error'] = 'Unesena količina nije broj';
    }
}


// End Cart





// Form


function validateForm()
{
    $errors = array();
    if (empty($_POST['name'])) {
        $errors[] = 'Polje za ime je obavezno';
        unset($_SESSION['name']);
    } else {
        $_SESSION['name'] = $_POST['name'];
    }
    if (empty($_POST['surname'])) {
        $errors[] = 'Polje za prezime je obavezno';
        unset($_SESSION['surname']);
    } else {
        $_SESSION['surname'] = $_POST['surname'];
    }
    if (empty($_POST['address'])) {
        $errors[] = 'Polje za adresu je obavezno';
        unset($_SESSION['address']);
    } else {
        $_SESSION['address'] = $_POST['address'];
    }
    if (empty($_POST['email'])) {
        $errors[] = 'Polje za email je obavezno';
        unset($_SESSION['email']);
    } else {
        $_SESSION['email'] = $_POST['email'];
    }

    return $errors;
}

// End Form






// Utilities



function displayCats($parent = 0, $level = 0)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id,name FROM categories WHERE parent_id = $parent");
    $stmt->execute();
    $results = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($results)) {
?>
        <div class="border-2 shadow-lg p-3 hover:shadow-xl bg-white">
            <div class="flex justify-center mb-1">
                <a href="category.php?id=<?php echo $row['id'] ?>&sort=id*DESC">
                    <p class="font-medium text-2xl text-gray-500 rounded-lg mx-auto inline-block px-2 hover:text-yellow-600"><?php echo $row['name'] ?></p>
                </a>
            </div>
            <hr class="mb-2 border-5 border-gray-300">
            <?php display_children2($row['id'], 0); ?>
        </div>
    <?php
    }
}


function display_children2($parent, $level)
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
            <div style="margin-left:<?php echo $level * 30 ?>px; <?php if ($level == 0) echo 'margin-top:15px' ?>" class=" text-md">
                <a href="category.php?id=<?php echo $row['id'] ?>&sort=id*DESC" class="hover:text-yellow-500">
                    <li><?php echo $row['name'] ?></li>
                </a>
            </div>

        <?php

            display_children2($row['id'], $level + 1);
        }
        ?>
    </ul>
<?php
}


function getChildren($parent, $level)
{
    global $conn, $categories;
    $result = $conn->query('SELECT id,name FROM categories ' . 'WHERE parent_id="' . $parent . '";');
    // display each child 

    while ($row = mysqli_fetch_array($result)) {
        // indent and display the title of this child 
        $categories[] = $row['id'];
        getChildren($row['id'], $level + 1);
    }
    return $categories;
}


function getFinalPrice($price, $discount)
{
    $discount = (100 - $discount) * 0.01;
    $price = number_format($price * $discount, 2);
    return $price;
}

function getFloatPrice($price, $discount)
{
    $discount = (100 - $discount) * 0.01;
    $price = $price * $discount;
    return $price;
}


function getAvaliableStock($item)
{
    global $conn;
    $stmt = $conn->prepare("SELECT avaliable_stock FROM items WHERE item_id = ?");
    $stmt->bind_param('i', $item);
    $stmt->execute();
    $result = $stmt->get_result();
    $selectedItem = mysqli_fetch_assoc($result);
    $avaliableStock  = $selectedItem['avaliable_stock'];
    return $avaliableStock;
}








function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function paginationPart1($allCats, $sort)
{
    $limit = 1;
    $resultsItems = '';
    global $conn;
    $sql = "SELECT items.name,items.base_price,items.id,items.discount,categories.name as category FROM items INNER JOIN item_category ON item_category.item_id = items.id JOIN categories ON categories.id 
    = item_category.category_id WHERE ";
    $many = '';
    $params = '';
    $count = 0;
    if (sizeof($allCats) > 1) {
        foreach ($allCats as $cat) {
            if ($count == 0) {
                $many .= 'categories.id = ? ';
                $params .= 'i';
                $count++;
                continue;
            }
            $many .= ' OR categories.id = ? ';
            $params .= 'i';
        }
        $sql = $sql . $many . ' GROUP BY items.name ORDER BY ' . $sort[0] . ' ' . $sort[1];
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($params, ...$allCats);
        $stmt->execute();
        $results = $stmt->get_result();
    } else {
        $stmt = $conn->prepare($sql . ' categories.id = ?');
        $stmt->bind_param('i', $allCats[0]);
        $stmt->execute();
        $results = $stmt->get_result();
    }

    $allRecrods = mysqli_num_rows($results);
    // Calculate total pages
    $totoalPages = ceil($allRecrods / $limit);
    // Current pagination page number
    $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
    $prev = $page - 1;
    $next = $page + 1;
    // Offset
    $paginationStart = ($page - 1) * $limit;
    if ($allRecrods != 0) {
        $stmt = $conn->prepare($sql . " LIMIT $paginationStart, $limit");
        $stmt->bind_param($params, ...$allCats);
        $stmt->execute();
        $resultsItems = $stmt->get_result();
    }


    return array($resultsItems, $limit, $page, $prev, $next, $totoalPages);
}



function paginationPart2($id, $sortField, $sortWay)
{
    global $totoalPages, $page, $prev, $next;
?>

    <!--Pagination -->
    <nav class="d-flex justify-content-center wow fadeIn">
        <ul class="flex space-x-2">
            <!--Arrow left-->
            <a class="page-link " href="<?php if ($page <= 1) {
                                            echo '#';
                                        } else {
                                            echo '?page=' . $prev . '&sort=' . $sortField . '*' . $sortWay . '&id=' . $id;
                                        } ?> " aria-label="Previous">
                <li class="page-item  <?php if ($page <= 1) {
                                            echo 'disabled';
                                        } ?> bg-gray-600 text-white hover:bg-yellow-500 p-2 transition ease-in rounded-md">

                    <span aria-hidden="true"> &lArr;</span>
                    <span class="sr-only">Previous</span>
                </li>
            </a>

            <?php
            for ($i = 1; $i <= $totoalPages; $i++) {
            ?>
                <a class="page-link" href="<?php echo 'category' . '.php?page=' . $i . '&sort=' . $sortField . '*' . $sortWay . '&id=' . $id ?>">
                    <li class="page-item <?php if ($page == $i) {
                                                echo 'bg-yellow-500';
                                            } else {
                                                echo 'bg-gray-600';
                                            }; ?>  text-white hover:bg-yellow-500 py-2 px-3 transition ease-in rounded-lg">
                        <?php echo $i ?>
                        <?php if ($page == $i) {
                        ?>
                            <span class="sr-only">(current)</span>
                        <?php
                        } ?>


                    </li>
                </a>
            <?php
            }
            ?>
            <a class="page-link" href="<?php if ($page >= $totoalPages) {
                                            echo '#';
                                        } else {
                                            echo '?page=' . $next . '&sort=' . $sortField . '*' . $sortWay . '&id=' . $id;
                                        } ?> " aria-label="Next">
                <li class="page-item <?php if ($page >= $totoalPages) {
                                            echo 'disabled';
                                        } ?> bg-gray-600 text-white hover:bg-yellow-500 p-2 transition ease-in rounded-md ">

                    <span aria-hidden="true">&rArr;</span>
                    <span class="sr-only">Next</span>

                </li>
            </a>
        </ul>
    </nav>
    <!--Pagination -->
    <?php

}

// End Utilities








function display_children($parent, $level)
{

    // retrieve all children of $parent 
    global $conn;
    $result = $conn->query('SELECT id,name FROM categories ' . 'WHERE parent_id="' . $parent . '";');
    // display each child 

    while ($row = mysqli_fetch_array($result)) {
        // indent and display the title of this child 
    ?>
        <div class="form-check" style="<?php echo 'margin-left:' . 20 * $level . 'px' ?>">
            <label class="form-check-label" style="font-size: 15px;">
                <input class="form-check-input" name="categories[]" type="checkbox" value="<?php echo $row['id'] ?>">
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

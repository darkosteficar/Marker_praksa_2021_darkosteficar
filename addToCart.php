<?php
include("includes/db.php");
include("includes/functions.php");
session_start();
global $conn;
$error = '';
if (isset($_POST['quantity'])) {
    $quantity = $_POST['quantity'];
} else {
    $quantity = 1;
}
$id = $_POST['id'];
$cart_id = $_POST['cart_id'];
$oldQuantity = 0;


$stmt = $conn->prepare("SELECT item_id,quantity FROM cart_items1 WHERE item_id = ? AND cart_id = ?");
$stmt->bind_param('is', $id, $cart_id);
$stmt->execute();
$results = $stmt->get_result();
$check = mysqli_num_rows($results);
if ($check > 0) {
    $quan = mysqli_fetch_assoc($results);
    $oldQuantity = $quan['quantity'];
}

$stmt = $conn->prepare("SELECT item_id FROM cart_items1 WHERE cart_id = ? ");
$stmt->bind_param('s', $cart_id);
$stmt->execute();
$results = $stmt->get_result();
$size = mysqli_num_rows($results);



$stmt = $conn->prepare("SELECT items.name,items.base_price,images.path,items.avaliable_stock,items.discount FROM items INNER JOIN images ON images.item_id = items.id WHERE items.id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$resultsItem = $stmt->get_result();
$item = mysqli_fetch_assoc($resultsItem);
$itemName = $item['name'];
$itemPrice = $item['base_price'];
$image = $item['path'];
$disc_price = getFinalPrice($itemPrice, $item['discount']);


if (is_numeric($quantity)) {
    if ($quantity > $item['avaliable_stock']) {
        $error = 'Unesena količina prekoračuje dostupnu količinu,dostupna količina: ' . $item['avaliable_stock'];
    }
} else {
    $error = 'Unesena količina nije broj. ';
}


if ($error == '') {
    if ($check == 0) {
        $stmt = $conn->prepare("INSERT INTO cart_items1 (cart_id,item_id,quantity) VALUES (?,?,?)");
        $stmt->bind_param('sii', $cart_id, $id, $quantity);
        $size++;
    } else {
        $stmt = $conn->prepare("UPDATE cart_items1 SET quantity = quantity + $quantity WHERE item_id = ? AND cart_id = ?");
        $stmt->bind_param('is', $id, $cart_id);
    }

    if ($stmt->execute()) {
        echo json_encode(array(
            'success' => 1, 'quantity' => $size, 'name' => $itemName, 'base_p' => $itemPrice,
            'image' => $item['path'], 'dis_p' => $disc_price, 'id' => $id
        ));
    } else {
        $error = $conn->error;
        echo json_encode(array('success' => 0, 'error' => $error));
    }
} else {
    echo json_encode(array('success' => 0, 'error' => $error, 'image' => $item['path']));
}

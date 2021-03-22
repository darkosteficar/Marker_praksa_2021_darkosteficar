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



$stmt = $conn->prepare("UPDATE cart_items1 SET quantity = quantity + $quantity WHERE item_id = ? AND cart_id = ?");
$stmt->bind_param('is', $id, $cart_id);
$stmt->execute();

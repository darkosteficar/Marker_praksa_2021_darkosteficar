<?php
include_once('includes/db.php');
session_start();
global $conn;
$cart_id = session_id();
$stmt = $conn->prepare("SELECT cart_id FROM cart_items1 WHERE cart_id = ?");
$stmt->bind_param('s', $cart_id);
$stmt->execute();
$result = $stmt->get_result();
$num = mysqli_num_rows($result);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colfax</title>
    <link rel="stylesheet" href="public/styles.css">
</head>

<body class="font-body bg-gray-200">
    <header>
        <div class="bg-yellow-400 space-x-2 py-2 items-center justify-between flex">
            <div class="flex space-x-2 items-center">
                <img src="images/logo.png" alt="" width="50">
                <a href="index.php">
                    <p class="text-2xl font-bold hover:text-white">Colfax</p>
                </a>
                <a href="admin/items.php">
                    <p class="text-xl font-semibold hover:text-white ml-4">Admin</p>
                </a>


            </div>
            <div class="flex items-center">
                <div class="relative text-gray-600 mr-4 pr-4 ">
                    <input type="search" name="serch" placeholder="Pretraga" class="bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none shadow-lg">
                    <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                        <img src="images/search.png" alt="" class="h-4 w-4 fill-current mr-4">
                    </button>
                </div>
                <a href="cart.php">
                    <div class="hover:bg-yellow-100 mr-4 rounded-lg px-2 bg-white shadow-lg">
                        <div class="flex items-center">
                            <img src="images/cart.png" alt="" width="50" class="mr-2">
                            <p class="font-bold text-lg px-2 text-yellow-400" id="cart_count"> <?php echo $num ?></p>

                        </div>
                    </div>
                </a>
            </div>

        </div>
    </header>
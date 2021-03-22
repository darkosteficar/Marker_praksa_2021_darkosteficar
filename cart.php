<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");
global $cart_id;

$items = getCartContent($cart_id);
$total = array();


if (isset($_GET['delete'])) {
    cartDelete($cart_id);
}


if (isset($_POST['edit'])) {

    global $conn, $cart_id;
    $item = test_input($_POST['item']);
    $quantity = test_input($_POST['quantity']);

    $stmt = $conn->prepare("SELECT avaliable_stock FROM items WHERE item_id = ?");
    $stmt->bind_param('i', $item);
    $stmt->execute();
    $result = $stmt->get_result();
    $selectedItem = mysqli_fetch_assoc($result);
    $avaliableStock  = $selectedItem['avaliable_stock'];

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


?>



<main>
    <div class="bg-yellow-500 py-10 mt-2 relative ">
        <div class="w-7/12 mx-auto h-24">
            <div class="flex items-center">
                <p class="text-5xl">
                    Košarica
                </p>
                <p class="text-white text-xl ml-2">
                    (1)
                </p>
            </div>

        </div>
        <div class="absolute w-full top-28">
            <div class="w-2/3 mx-auto bg-white shadow-lg rounded-lg ">
                <div class="flex justify-center space-x-5">
                    <div class="w-2/3 ml-6">
                        <?php
                        if (isset($_SESSION['success'])) {
                            echo '<div class="bg-green-400 mt-2 text-xl font-semibold rounded-md p-3" role="alert">' .  $_SESSION['success'] . '</div>';
                            unset($_SESSION['success']);
                        }
                        if (isset($_SESSION['danger'])) {
                            echo '<div class="bg-red-500 mt-2 text-xl font-semibold rounded-md p-3" text-white role="alert">' .  $_SESSION['danger'] . '</div>';
                            unset($_SESSION['danger']);
                        }
                        ?>
                        <?php while ($row = mysqli_fetch_assoc($items)) {
                            list($image, $overallNoDiscount, $overallDiscount) = cartInfo($row);

                        ?>
                            <form action="cart.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                <div class="flex justify-between p-5">
                                    <div class="flex">
                                        <div class="mr-5">
                                            <div class="border-2 border-gray-100 p-1">
                                                <img src="admin/images/<?php echo $image ?>" alt="" width="80">
                                            </div>
                                            <a href="cart.php?delete=<?php echo $row['id'] ?>&item=<?php echo $row['name'] ?>">
                                                <div class="flex items-center justify-center mt-3 space-x-1 hover:bg-yellow-500 p-1 bg-yellow-400 rounded-xl shadow-lg">
                                                    <img src="images/trash.png" alt="" width="15">
                                                    <p class="text-xs">Ukloni</p>
                                                </div>
                                            </a>
                                        </div>
                                        <div><?php echo $row['name'] ?></div>

                                    </div>
                                    <div class="my-auto">
                                        <div class="flex items-center space-x-3">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input type="hidden" value="<?php echo $row['id'] ?>" name="item">
                                                <div>
                                                    <input type="text" class="w-14 h-10 text-center border-gray-100 border-2 rounded focus:border-yellow-400 focus:outline-none" value="<?php echo $row['quantity'] ?>" name="quantity">
                                                </div>
                                                <div>
                                                    <button type="submit" class="   w-8 h-8 rounded-full hover:bg-yellow-400 hover:border-gray-400 transition ease-in" name="edit">
                                                        <img src="images/refresh.png" alt="">
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="flex items-center justify-center mt-2">
                                            <p class="text-sm text-gray-400">KOM</p>
                                        </div>
                                    </div>

                                    <div class="my-auto">
                                        <strike>
                                            <p class="text-right"><?php echo $overallNoDiscount ?> kn</p>
                                        </strike>
                                        <p class="text-2xl text-yellow-400 font-bold"><?php echo  $overallDiscount ?> kn</p>
                                        <p class="text-right"><?php echo $row['quantity'] ?> x <?php echo getFinalPrice($row['base_price'], $row['discount']) ?> kn</p>
                                    </div>
                                </div>
                                <hr>
                            </form>

                        <?php
                        } ?>


                    </div>
                    <div class="w-1/3 border-l-2 border-gray-100 ">
                        <div class="flex flex-col px-8 pt-6">
                            <div class="text-yellow-500 text-2xl mb-6">Ukupno u košarici</div>
                            <div>
                                <div class="mx-auto">
                                    <div class="flex justify-between items-center">
                                        <p>Dostava:</p>
                                        <p>Besplatna</p>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p>Ukupno bez PDV-a:</p>
                                        <?php $noVAT = array_sum($total) * 0.75; ?>
                                        <p><?php echo number_format((float)$noVAT, 2, '.', ',')   ?> kn</p>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p>Ukupan PDV (25%):</p>
                                        <?php $noVAT = array_sum($total) * 0.25; ?>
                                        <p><?php echo number_format((float)$noVAT, 2, '.', ',')   ?> kn</p>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="font-semibold text-xl">Sveukupno:</p>
                                        <p class="font-semibold text-xl"><?php echo number_format((float)array_sum($total), 2, '.', ',')  ?> kn</p>
                                    </div>

                                </div>

                            </div>
                            <div class="mx-auto mt-10">
                                <a href="form.php">
                                    <button type="button" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-300 to-yellow-500 transform hover:scale-105 transition ease-in ">
                                        <div class="flex items-center space-x-3">
                                            <img src="images/data.png" alt="" width="40">
                                            <p class="font-bold text-lg"> Unos podataka</p>
                                        </div>
                                    </button>
                                </a>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>










</main>
</body>

</html>
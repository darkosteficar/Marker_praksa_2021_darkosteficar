<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");



$items = getCartContent($cart_id);

if (!isset($_POST['order'])) {
    $errors = validateForm();
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('location: form.php');
        exit();
    }
    $_SESSION['name'] = test_input($_POST['name']);
    $_SESSION['surname'] = test_input($_POST['surname']);
    $_SESSION['email'] = test_input($_POST['email']);
    $_SESSION['address'] = test_input($_POST['address']);
} else {

    $time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO orders (name,surname,email,address,time) VALUES (?,?,?,?,?)");
    $stmt->bind_param('sssss', $_SESSION['name'], $_SESSION['surname'], $_SESSION['email'], $_SESSION['address'], $time);
    $stmt->execute();
    $order_id = mysqli_insert_id($conn);

    while ($row = mysqli_fetch_assoc($items)) {
        list($image, $overallNoDiscount, $overallDiscount) = cartInfo($row);
        $stmt = $conn->prepare("INSERT INTO order_items (order_id,item_id,quantity,price) VALUES (?,?,?,?)");
        $stmt->bind_param('iidd', $order_id, $row['id'], $row['quantity'], getFloatPrice($row['base_price'], $row['discount']));
        $stmt->execute();
    }
    header('location: thankYou.php');
}




?>



<main>
    <div class="bg-yellow-500 py-10 mt-2 relative ">
        <div class="w-7/12 mx-auto h-24">
            <div class="flex items-center">
                <p class="text-4xl">
                    Pregled prije narudžbe
                </p>
            </div>

        </div>
        <div class="absolute w-full top-28">
            <div class="w-2/3 mx-auto bg-white shadow-lg rounded-lg ">

                <div class="flex justify-center space-x-5">
                    <div class="w-2/3 ml-6">
                        <p class="px-4 pt-5 text-yellow-500">Proizvodi:</p>

                        <?php while ($row = mysqli_fetch_assoc($items)) {
                            list($image, $overallNoDiscount, $overallDiscount) = cartInfo($row); ?>


                            <!-- Item -->
                            <div class="flex justify-between p-5">
                                <div class="flex">
                                    <div class="mr-5">
                                        <div class="border-2 border-gray-100 p-1">
                                            <img src="admin/images/<?php echo $image ?>" alt="" width="80">
                                        </div>

                                    </div>
                                    <div><?php echo $row['name'] ?></div>
                                </div>
                                <div class="my-auto">

                                    <p class="text-right">Količina: <?php echo $row['quantity'] ?></p>
                                    <p class="text-2xl text-yellow-400 font-bold"><?php echo $overallDiscount ?> kn</p>
                                    <p class="text-right"><?php echo $row['quantity'] ?> x <?php echo getFinalPrice($row['base_price'], $row['discount']) ?> kn</p>
                                </div>
                            </div>
                            <hr>
                            <!-- End Item -->


                        <?php
                        } ?>


                        <p class="px-4 pt-5 text-yellow-500">Podaci u kupcu:</p>
                        <div class="flex flex-col space-y-1 my-5 ml-5">
                            <p>Ime: <?php echo $_SESSION['name'] ?></p>
                            <p>Prezime: <?php echo $_SESSION['surname'] ?></p>
                            <p>Adresa: <?php echo $_SESSION['address'] ?></p>
                            <p>Email: <?php echo $_SESSION['email'] ?></p>
                        </div>



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
                            <form method="post" enctype="multipart/form-data">
                                <div class="mx-auto mt-10">
                                    <a href="thankYou.php">
                                        <button type="submit" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-100 to-yellow-500 transform hover:scale-105 transition ease-in " name="order">
                                            <div class="flex items-center space-x-3">
                                                <img src="images/confirm.png" alt="" width="40">
                                                <p class="font-bold text-lg"> Završi narudžbu</p>
                                            </div>
                                        </button>
                                    </a>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>



</main>
</body>

</html>
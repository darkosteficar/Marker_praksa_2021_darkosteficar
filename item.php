<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");

$id = $_GET['id'];
list($info, $images, $categories) = array_values(getItem($id));
// list($info, $images, $categories) = getItem($id);
$info = mysqli_fetch_assoc($info);
$firstImage = $images;
$image = mysqli_fetch_assoc($firstImage);
$price = getFinalPrice($info['base_price'], $info['discount'])
?>



<main>

    <div class="mt-10">
        <div class="flex flex-row w-9/12 justify-center  m-auto">
            <div class="w-1/3">
                <img src="admin/images/<?php echo $image['path'] ?>" alt="">
            </div>
            <div class="w-1/3 shadow-2xl pt-10 pr-10 ml-5 rounded-md">
                <div class="ml-32 space-y-2">
                    <div>
                        <p class="font-semibold text-4xl text-yellow-500"><?php echo $info['name'] ?></p>
                    </div>
                    <div class="flex">
                        <strike>
                            <p class="font-bold text-md  "><?php echo $info['base_price'] ?> kn</p>
                        </strike>
                        <p class="font-bold text-xl  ml-4">Cijena: <?php echo $price ?> kn</p>
                    </div>
                    <div>
                        <p class="font-semibold text-xl text-yellow-500">Proizvođač: <?php echo $info['brand'] ?></p>
                    </div>
                    <div class="flex">
                        <div class="w-1/3">Kategorije:</div>
                        <div class="w-2/3">
                            <?php
                            while ($category = mysqli_fetch_assoc($categories)) { ?>

                                <p><?php echo $category['name'] ?></p>
                            <?php
                            }
                            ?>
                        </div>

                    </div>
                    <div class="mt-6">
                        <form id="form">
                            <div class="mb-2 flex items-center">
                                <p class="text-lg mr-4">Količina: </p>
                                <input type="text" name="quantity" class="outline-none focus:border-yellow-500 border bg-gray-400 text-white w-1/4 h-8 text-center text-xl rounded-md" value="1">
                                <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id">
                                <input type="hidden" value="<?php echo session_id() ?>" name="cart_id">
                            </div>
                            <button type="submit" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-200 to-yellow-400
                             transform hover:scale-110 transition ease-in clickMe">
                                <div class="flex items-center">
                                    <img src="images/cart.png" alt="" width="50" class="mr-2">
                                    <p class="font-bold text-lg"> Dodaj u košaricu</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <div class="shadow-xl mt-32">
        <div class="flex flex-col mt-6">
            <p class="text-xl mx-auto font-bold ">
                Slike
            </p>
        </div>
        <div class="flex justify-center flex-wrap py-10">
            <img src="admin/images/<?php echo $image['path'] ?>" alt="" width="600">

            <?php while ($row = mysqli_fetch_assoc($images)) {
            ?>
                <img src="admin/images/<?php echo $row['path'] ?>" alt="" width="400">
            <?php
            }
            ?>

        </div>
    </div>

    <div class="flex flex-col justify-center mt-10 shadow-2xl pt-5 pb-10">
        <p class="text-xl mx-auto font-bold mb-4">
            Opis
        </p>

        <p class="w-3/6 mx-auto">
            <?php echo $info['description'] ?>
        </p>
    </div>





</main>

<?php include("includes/modals-item.php"); ?>
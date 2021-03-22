<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");


$items = getAllHighlighted();




?>



<main>
    <div class="flex justify-center">
        <div class="">
            <p class="my-2 text-4xl text-yellow-400 font-semibold ">KATEGORIJE</p>
        </div>
    </div>
    <div class="flex justify-center ">

        <div class="flex space-x-6">
            <?php displayCats();  ?>
        </div>

    </div>

    <div class="flex justify-center mt-6">
        <div class="">
            <p class="my-2 text-4xl text-yellow-400 font-semibold ">IZDVOJENO</p>
        </div>
    </div>
    <div class="flex flex-wrap justify-center mx-7">
        <?php
        while ($row = mysqli_fetch_assoc($items)) { ?>
            <?php
            $price = getFinalPrice($row['base_price'], $row['discount']);
            ?>
            <div class="shadow-md rounded-md bg-white p-5 mr-2 ml-2 hover:shadow-2xl mb-2">
                <div>
                    <img src="admin/images/<?php echo $row['path'] ?>" alt="product-image" width="250" class="mb-2" style="height: 210px;">
                </div>
                <div class="text-black flex justify-center mb-2 font-semibold">
                    <p class="text-2xl"><?php echo $row['name'] ?></p>
                </div>
                <div class="ml-3 flex">
                    <div class="font-bold text-yellow-500 mt-1 mr-4">Popust: 10%</div>
                    <div class="font-bold text-lg"> <?php echo $price ?> kn</div>
                </div>
                <div class="flex justify-center">
                    <div class="inline-block mr-2 mt-1">
                        <div class="flex">
                            <a href="item.php?id=<?php echo $row['id'] ?>"><button type="button" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-400 to-yellow-600 transform hover:scale-110 transition ease-in mr-2">Detalji</button></a>
                            <form id="form<?php echo $row['id'] ?>" class="form">
                                <input type="hidden" value="<?php echo $row['id'] ?>" id="idItem<?php echo $row['id'] ?>" name="id">
                                <input type="hidden" value="<?php echo session_id() ?>" name="cart_id">
                                <button id="idButton<?php echo $row['id'] ?>" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-400 to-yellow-600 transform hover:scale-110 transition ease-in clickMe">Dodaj u košaricu</button>
                            </form>
                        </div>

                    </div>
                </div>



            </div>


        <?php
        }


        ?>







    </div>


    <!-- Modal Success -->
    <div class="modal h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <!-- modal -->
        <div class="bg-white rounded shadow-lg w-2/5 p-4">
            <!-- modal header -->
            <div class=" px-4 py-6 flex justify-between items-center space-x-3 ">
                <div class="flex pr-12">
                    <img src="images/confirmCart.png" alt="" width="30" class="mr-2">
                    <p class=" text-2xl text-yellow-400">Proizvod je dodan u košaricu.</p>
                </div>

                <div class="flex items-center">
                    <button class="close-modal">
                        <p class="mr-1">Nastavi kupovati</p>
                    </button>
                    <img src="images/rightArrow.png" alt="" width="25">
                </div>
            </div>
            <!-- modal body -->
            <div class="px-12 py-3 flex justify-left items-center" id="modal-content">
                <img src="" alt="" width="150" class=" mr-12" id="item_image_success">
                <div class="flex flex-col space-y-4">
                    <div>
                        <p class=" text-xl font-light " id="item_name">Xiaomi Mi 9T 128 GB: Plavi</p>
                        <div class="flex text-sm mt-1">
                            <p class="text-gray-400 mr-1">Šifra: </p>
                            <p id="item_id">161004</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm" id="item_base_price"><strike> 1,951.01 kn</strike></p>

                        <p class="text-xl font-semibold" id="item_price_discount">1.511,04 kn</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-center items-center w-100  space-x-2 mx-6 mt-10 mb-10">
                <button class="from-yellow-400 to-yellow-600 bg-gradient-to-r w-full rounded h-14 
            text-white" onclick="location.href = 'cart.php';">Pregled košarice</button>

                <button class="from-gray-400 to-gray-600 bg-gradient-to-r w-full rounded h-14 
            text-white" onclick="location.href = 'form.php';">Dovrši kupovinu</button>
            </div>
        </div>
    </div>
    <!-- Modal Success -->

    <!-- Modal Danger -->
    <div class="modal-error h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <!-- modal -->
        <div class="bg-white rounded shadow-lg w-2/5 p-4">
            <!-- modal header -->
            <div class=" px-4 py-6 flex justify-between items-center space-x-3 ">
                <div class="flex pr-12">
                    <img src="images/error.png" alt="" width="30" class="mr-2">
                    <p class=" text-2xl text-red-600">Dogodila se greška</p>
                </div>

                <div class="flex items-center">
                    <button class="close-modal">
                        <p class="mr-1">Nastavi kupovati</p>
                    </button>
                    <img src="images/rightArrow.png" alt="" width="25">


                </div>

            </div>
            <!-- modal body -->
            <div class="px-12 py-3 flex justify-left items-center" id="modal-content">
                <img src="" alt="" width="150" class=" mr-12" id="item_image">
                <div class="flex flex-col space-y-4">
                    <div id="error-content" class="text-xl font-bold">

                    </div>

                </div>

            </div>
            <div class="flex justify-center items-center w-100  space-x-2 mx-6 mt-10 mb-10">
                <button class="from-yellow-400 to-yellow-600 bg-gradient-to-r w-full rounded h-14 
            text-white" onclick="location.href = 'cart.php';">Pregled košarice</button>


            </div>
        </div>
    </div>
    <!-- Modal Danger -->

    <script>
        const modal = document.querySelector('.modal');
        const modal_error = document.querySelector('.modal-error');
        const showModal = document.querySelector('.show-modal');
        const closeModal = document.querySelectorAll('.close-modal');


        closeModal.forEach(close => {
            close.addEventListener('click', function() {
                modal.classList.add('hidden')
                modal_error.classList.add('hidden')

            });
        });
    </script>
    <!-- Modal -->



</main>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {


        var container = $('.modal');
        var error_container = $('.modal-error');
        var error_content = $('#error-content');
        var formID;
        var item_name = $('#item_name');
        var item_id = $('#item_id');
        var item_base_price = $('#item_base_price');
        var item_price_discount = $('#item_price_discount');
        $('.clickMe').click(function() {

            var btnId = this.id;
            var result = btnId.split('Button');
            formID = result[1];

        });

        $(".form").submit(function(event) {

            // Prevent default posting of form - put here to work in case of errors
            event.preventDefault();


            $.ajax({
                type: "POST",
                url: 'addToCart.php',
                data: $(this).serialize(),
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    // user is logged in successfully in the back-end
                    // let's redirect
                    if (jsonData.success == "1") {
                        container.removeClass('hidden');
                        item_name.html(jsonData.name);
                        item_id.html(jsonData.id);
                        item_base_price.html(jsonData.base_p + ' kn');
                        item_price_discount.html(jsonData.dis_p + ' kn');
                        $('#item_image_success').attr("src", "admin/images/" + jsonData.image);
                        $('#cart_count').html(jsonData.quantity);

                    } else {
                        error_container.removeClass('hidden');
                        error_content.html(jsonData.error);
                        $('#item_image').attr("src", "admin/images/" + jsonData.image);
                    }
                }
            });


        });


    });
</script>
</body>

</html>
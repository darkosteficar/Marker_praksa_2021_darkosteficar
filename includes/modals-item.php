<!-- Modal Success -->
<button class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-white m-5 show-modal">show modal</button>

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

    showModal.addEventListener('click', function() {
        modal.classList.remove('hidden')

    });

    closeModal.forEach(close => {
        close.addEventListener('click', function() {
            modal.classList.add('hidden')
            modal_error.classList.add('hidden')
        });
    });
</script>
<!-- Modal -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        $('.clickMe').click(function() {

            var container = $('.modal');
            var error_container = $('.modal-error');
            var error_content = $('#error-content');
            var item_name = $('#item_name');
            var item_id = $('#item_id');
            var item_base_price = $('#item_base_price');
            var item_price_discount = $('#item_price_discount');
            $("#form").submit(function(event) {
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
                            item_id.html(jsonData.quantity);
                            item_base_price.html(jsonData.base_p + ' kn');
                            item_price_discount.html(jsonData.dis_p + ' kn');
                            $('#item_image_success').attr("src", "admin/images/" + jsonData.image);
                            $('#cart_count').html(jsonData.quantity);
                            console.log(jsonData.quantity);

                        } else {
                            error_container.removeClass('hidden');
                            error_content.html(jsonData.error);
                            $('#item_image').attr("src", "admin/images/" + jsonData.image);
                        }
                    }
                });


            });


        });
    });
</script>

</body>

</html>
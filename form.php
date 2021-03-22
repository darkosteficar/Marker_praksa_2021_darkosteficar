<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");


$errors = array();


?>



<main>
    <div class="h-5 mt-6">

    </div>
    <form method="post" enctype="multipart/form-data" action="preview.php">
        <div class="w-1/4 mx-auto relative mt-5 ">

            <div class="card bg-yellow-400 shadow-lg  w-full h-full rounded-3xl absolute  transform -rotate-6"></div>
            <div class="card bg-gray-400 shadow-lg  w-full h-full rounded-3xl absolute  transform rotate-6"></div>
            <div class="bg-white shadow-lg p-6 rounded-lg mt-5 space-y-4 text-left relative ">
                <div class="flex justify-center mb-3">
                    <p>Podaci za dostavu</p>
                </div>
                <hr>
                <div>
                    <?php
                    if (isset($_SESSION['errors'])) {
                        $errors = $_SESSION['errors'];
                        foreach ($errors as $error) {
                            echo '<div class="bg-red-500 mt-2 text-lg text-white shadow-lg rounded-md p-3" role="alert">' .  $error . '</div>';
                        }
                        unset($_SESSION['errors']);
                    }


                    ?>
                </div>
                <div>
                    <input type="text" class="focus:border-yellow-500 focus:bg-yellow-100 
                bg-white outline-none text-lg rounded h-10 pl-2 shadow-xl" placeholder="Ime" name="name" value="<?php if (isset($_SESSION['name'])) echo $_SESSION['name'] ?>">
                </div>
                <div>
                    <input type="text" class="focus:border-yellow-500 focus:bg-yellow-100 
                bg-white outline-none text-lg rounded h-10 pl-2 shadow-xl" placeholder="Prezime" name="surname" value="<?php if (isset($_SESSION['surname'])) echo $_SESSION['surname'] ?>">
                </div>

                <div>
                    <input type="text" class="focus:border-yellow-500 focus:bg-yellow-100 
                bg-white outline-none text-lg rounded h-10 pl-2 shadow-xl" placeholder="Adresa" name="address" value="<?php if (isset($_SESSION['email'])) echo $_SESSION['email'] ?>">
                </div>
                <div>
                    <input type="text" class="focus:border-yellow-500 focus:bg-yellow-100 
                bg-white outline-none text-lg rounded h-10 pl-2 shadow-xl" placeholder="Email" name="email" value="<?php if (isset($_SESSION['address'])) echo $_SESSION['address'] ?>">
                </div>

                <hr style="margin-top:20px">
                <div>

                    <button type="submit" name="send" class="mt-10 focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-300 to-yellow-500 transform hover:scale-105 transition ease-in w-full">
                        <div class="flex items-center space-x-3 justify-center">
                            <img src="images/next.png" alt="" width="30">
                            <p class="font-bold text-lg "> Pregled narudžbe</p>
                        </div>
                    </button>

                </div>

            </div>
        </div>
    </form>

</main>
</body>

</html>
<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");


$items = getAllHighlighted();




?>



<main>

    <div class=" w-full top-28 mt-6">
        <div class="w-2/3 mx-auto bg-white shadow-lg rounded-lg text-center">

            <p class="text-6xl font-bold mb-12 pt-10 text-yellow-400">Hvala vam!</p>
            <div class="flex items-center justify-center space-x-4">
                <img src="images/truck.png" alt="" width="70">
                <p class="text-2xl font-bold py-4">Vaša narudžba je uspješno zaprimljena.</p>
                <img src="images/truck.png" alt="" width="70">
            </div>

            <div class="text-xl font-semibold py-4 ">
                <p class="inline-block">Kliknite</p>
                <a href="index.php">
                    <p class="text-yellow-400 inline-block">ovdje</p>
                </a>
                <p class="inline-block"> kako biste se vratili na početnu stranicu.</p>

            </div>

        </div>

    </div>



</main>
</body>

</html>
<?php

include("includes/db.php");
include("includes/header.php");
include("includes/functions.php");

$id = $_GET['id'];
$categories = array();
$categories[] = $id;
$allCats = getChildren($id, 0);
$sort = explode('*', $_GET['sort']);

?>



<main>
    <div class="flex items-center space-x-2 mt-4 justify-center">
        <div class="mr-24">
            <p class="text-xl">Proizvodi iz odabrane kategorije:</p>
        </div>
        <div>
            <p>Sortiranje:</p>
        </div>
        <div class="relative inline-flex">
            <img src="images/sort.png" class="w-6 h-6 absolute  right-0 mr-28 mt-2 pointer-events-none " alt="">
            <form action="category.php" method="get" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                <select name="sort" class="border border-yellow-300 rounded-full text-black h-10 pl-5 pr-10 bg-gray-100 hover:border-gray-400 focus:outline-none appearance-none">
                    <option class="bg-gray-300 hover:bg-gray-700" value="id*DESC">Najnovije</option>
                    <option class="bg-gray-300 hover:bg-gray-700" value="id*ASC">Najstarije</option>
                    <option class="bg-gray-300 hover:bg-gray-700" value="base_price*DESC">Najskuplje</option>
                    <option class="bg-gray-300 hover:bg-gray-700" value="base_price*ASC">Najjeftinije</option>
                </select>
                <button type="submit" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-400 to-yellow-600 transform hover:scale-110 transition ease-in mr-2">Sortiraj</button>
            </form>
        </div>
        <div>
        </div>
    </div>

    <?php
    list($resultsItems, $limit, $page, $prev, $next, $totoalPages) = paginationPart1($allCats, $sort);

    ?>
    <div class="w-2/3 mx-auto">
        <div class="flex flex-wrap justify-center mx-7 mt-5 ">
            <?php
            if ($resultsItems != '') {
                while ($row = mysqli_fetch_assoc($resultsItems)) {
                    $ImagePath = getImage($row['id']);
            ?>
                    <div class="shadow-md rounded-md bg-white p-5 mr-2 ml-2 hover:shadow-2xl mb-2">
                        <div>
                            <img src="admin/images/<?php echo $ImagePath ?>" alt="product-image" width="250" class="mb-2" style="height: 210px;">
                        </div>
                        <div class="text-black flex justify-center mb-2 font-semibold">
                            <p class="text-2xl"><?php echo $row['name'] ?></p>
                        </div>
                        <div class="ml-3 flex">
                            <div class="font-bold text-yellow-500 mt-1 mr-4">Popust: 10%</div>
                            <div class="font-bold text-lg"> <?php echo $row['base_price'] ?> kn</div>
                        </div>
                        <div class="flex justify-center">
                            <div class="inline-block mr-2 mt-1">
                                <a href="item.php?id=<?php echo $row['id'] ?>"><button type="button" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-400 to-yellow-600 transform hover:scale-110 transition ease-in mr-2">Detalji</button></a>

                                <button type="button" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-gradient-to-r from-yellow-400 to-yellow-600 transform hover:scale-110 transition ease-in ">Dodaj u košaricu</button>
                            </div>
                        </div>

                    </div>

                <?php
                }
            } else { ?>
                <p class="font-bold text-xl text-yellow-500 mt-12">Nije pronađen ni jedan proizvod iz odabrane kategorije</p>
            <?php
            }
            ?>

        </div>
        <?php if ($resultsItems != '') { ?>
            <div class="flex justify-center mt-7">
                <?php
                paginationPart2($_GET['id'], $sort[0], $sort[1]);
                ?>
            </div>
        <?php
        }
        ?>
    </div>
</main>






</body>

</html>
<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();




if (isset($_POST['add'])) {
    $item = test_input($_POST['item']);
    $price = test_input($_POST['price']);
    $quantity = test_input($_POST['quantity']);
    $order = test_input($_POST['order']);
    $stmt = $conn->prepare("INSERT INTO order_items (item_id,order_id,price,quantity) VALUES (?,?,?,?)");
    $stmt->bind_param('iidi', $item, $order, $price, $quantity);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Proizvod je uspješno dodan u narudžbu!';
    };
}




?>

<body class="">
    <div class="wrapper">
        <div class="sidebar">
            <!--
            Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
             -->
            <?php
            include("includes/admin-sidebar.php");
            ?>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <?php
            include("includes/admin-navbar.php");
            ?>
            <!-- End Navbar -->
            <div class="content">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h4 class="card-title"> Dodaj Proizvod</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo  '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }

                                ?>
                                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">

                                    <input type="hidden" value="<?php echo $_GET['order'] ?>" name="order">
                                    <div class="form-group">
                                        <label for="inputState">Proizvod</label>
                                        <select class="custom-select" id="inputState" class="form-control" name="item" style="background-color: gray;">
                                            <?php
                                            $stmt = $conn->prepare("SELECT * FROM items");
                                            $stmt->execute();
                                            $results = $stmt->get_result();
                                            while ($row1 = mysqli_fetch_assoc($results)) {
                                            ?>
                                                <option value="<?php echo $row1['id'] ?>"><?php echo $row1['name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Cijena</label>
                                        <input name="price" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite cijenu.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Količina</label>
                                        <input name="quantity" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite količinu.
                                        </div>
                                    </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="margin-left: 10px;" name="add">Dodaj</button>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </div>
    </div>
    </div>
    <?php
    //include_once("includes/admin-fixed-plugin.php");
    include_once("includes/admin-footer.php");

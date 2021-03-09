<?php
include_once("includes/db.php");
include_once("includes/functions.php");
include_once("includes/admin-header.php");
ob_start();


if (isset($_POST['create'])) {
    if (createOrder()) {
        $_SESSION['success'] = 'Narudžba je uspješno dodana!';
    } else {
        echo '<div class="alert alert-danger" role="alert">
        Dogodila se greška.
        </div>';
    }
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
                                <h4 class="card-title"> Nova Narudžba</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo  '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Ime</label>
                                        <input name="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite ime.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Prezime</label>
                                        <input name="surname" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite prezime.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input name="email" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite email.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Adresa</label>
                                        <input name="address" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">
                                        <div class="valid-feedback">
                                            Super!
                                        </div>
                                        <div class="invalid-feedback">
                                            Molimo unesite adresu.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Status</label>
                                        <select class="custom-select" id="inputState" class="form-control" name="brand" style="background-color: gray;">

                                            <?php
                                            $stmt = $conn->prepare("SELECT * FROM order_statuses");
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
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="margin-left: 10px;" name="create">Kreiraj</button>

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

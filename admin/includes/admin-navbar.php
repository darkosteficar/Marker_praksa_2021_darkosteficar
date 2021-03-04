<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)">Admin panel</a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">


                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <?php

                        //$sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1"; // SQL with parameters
                        //$stmt = $conn->prepare($sql);
                        //$stmt->bind_param("i", $_SESSION['user_id']);

                        //$stmt->execute();
                        //$result = $stmt->get_result(); // get the mysqli result
                        //$user = $result->fetch_assoc(); // fetch data
                        //$row = mysqli_fetch_assoc($result);


                        //$user_image = $row["user_image"];

                        ?>
                        <div class="photo">
                            <img src="../userImages/<?php/* echo $user_image*/ ?>" alt="Profile Photo">
                        </div>

                        <b class="caret d-none d-lg-block d-xl-block"></b>
                        <p class="d-lg-none">

                        </p>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <li class="nav-link"><a href="admin-profile.php" class="nav-item dropdown-item">Profil</a></li>

                        <li class="dropdown-divider"></li>
                        <li class="nav-link"><a href="../logout.php" class="nav-item dropdown-item">Odjava</a></li>
                    </ul>
                </li>

                <li class="separator d-lg-none"></li>
            </ul>
        </div>
    </div>
    <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="tim-icons icon-simple-remove"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
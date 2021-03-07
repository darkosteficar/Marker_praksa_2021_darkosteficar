 <!-- Modal For Deleting -->
 <div class="modal fade modal-black" id="exampleModal1<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                     <i class="tim-icons icon-simple-remove"></i>
                 </button>
             </div>
             <div class="modal-body">
                 Jeste li sigurni da želite obrisati ovu narudžbu?
             </div>
             <div class="modal-footer">
                 <?php $_SESSION['prevUrl'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                 <a href="orders.php?delete=<?php echo $row['id'] ?>"><button type="button" class="btn btn-primary">Izbriši</button></a>


             </div>
         </div>
     </div>
 </div>


 <!-- Modal For Editing -->
 <div class="modal fade modal-black" id="exampleModal3<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg " role="document" style="vertical-align: top;">
         <div class=" modal-content">
             <div class=" modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Promjene u proizvodu</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                     <i class="tim-icons icon-simple-remove"></i>
                 </button>
             </div>
             <div class="modal-body">


                 <div class="card-body">

                     <form class="needs-validation" novalidate method="post" enctype="multipart/form-data">
                         <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                         <div class="form-group">
                             <label for="inputState">Status</label>
                             <select class="custom-select" id="inputState" class="form-control" name="brand" style="background-color: gray;">
                                 <option value="<?php echo $item['brand_id'] ?>">Bez promjene</option>
                                 <?php
                                    $stmt = $conn->prepare("SELECT * FROM order_statuses");
                                    $stmt->execute();
                                    $results = $stmt->get_result();
                                    while ($row = mysqli_fetch_assoc($results)) {
                                    ?>
                                     <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>

                                 <?php
                                    }
                                    ?>
                             </select>
                         </div>


                         <div class="form-group">

                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                             <button type="submit" class="btn btn-primary" name="edit">Spremi promjene</button>

                         </div>
                     </form>
                 </div>

             </div>

         </div>
     </div>
 </div>
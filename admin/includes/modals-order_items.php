 <!-- Modal For Deleting -->
 <div class="modal fade modal-black" id="exampleModal1<?php echo $row['item_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                     <i class="tim-icons icon-simple-remove"></i>
                 </button>
             </div>
             <div class="modal-body">
                 Jeste li sigurni da želite obrisati ovaj proizvod iz narudžbe?
             </div>
             <div class="modal-footer">
                 <?php $_SESSION['prevUrl'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                 <a href="order_items.php?delete=<?php echo $row['item_id'] ?>"><button type="button" class="btn btn-primary">Izbriši</button></a>


             </div>
         </div>
     </div>
 </div>


 <!-- Modal For Editing -->
 <div class="modal fade modal-black" id="exampleModal3<?php echo $row['item_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                         <input type="hidden" value="<?php echo $_GET['order'] ?>" name="order_id">
                         <input type="hidden" value="<?php echo $row['item_id'] ?>" name="id">
                         <div class="form-group">
                             <label for="exampleInputEmail1">Cijena</label>
                             <input name="price" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $row['price'] ?>" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">

                         </div>
                         <div class="form-group">
                             <label for="exampleInputEmail1">Količina</label>
                             <input name="quantity" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $row['quantity'] ?>" required oninvalid="this.setCustomValidity('Unesite naslov!')" oninput="this.setCustomValidity('')">

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